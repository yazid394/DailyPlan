<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subtask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Show the authenticated user's tasks with live statistics.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today();

        $tasks = $user->tasks()
            ->with(['category', 'subtasks'])
            ->orderBy('is_completed', 'asc')
            ->orderBy('date', 'asc')
            ->get();

        // Semua angka diambil dari database user yang sedang login, bukan data dummy.
        $perluPerhatian = $user->tasks()
            ->where('is_completed', false)
            ->whereDate('date', '<=', $today->copy()->addDays(2)->toDateString())
            ->count();

        $mingguIni = $user->tasks()
            ->where('is_completed', false)
            ->whereDate('date', '>=', $today->toDateString())
            ->whereDate('date', '<=', $today->copy()->addDays(7)->toDateString())
            ->count();

        $totalSelesai = $user->tasks()->where('is_completed', true)->count();
        $totalTugas = $user->tasks()->count();
        $tepatWaktu = $totalTugas === 0 ? '100%' : round($totalSelesai / $totalTugas * 100).'%';

        $stats = [
            'perlu_perhatian' => $perluPerhatian,
            'minggu_ini' => $mingguIni,
            'tepat_waktu' => $tepatWaktu,
        ];

        return view('task.index', compact('tasks', 'stats'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request): View
    {
        return view('task.edit', [
            'categories' => $this->categoriesFor($request),
        ]);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTask($request);

        $task = $request->user()->tasks()->create([
            'title' => $validated['title'],
            'notes' => $validated['description'] ?? null,
            'date' => $validated['due_date'],
            'deadline_date' => $validated['due_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'priority' => strtoupper($validated['priority']),
            'is_completed' => false,
        ]);

        $this->syncReminder($task, $validated['reminder_minutes'] ?? null);
        $this->syncSubtasks($task, $validated['subtasks'] ?? []);

        // Langsung arahkan ke Kalender dan bawa tanggal yang baru diisi
        return redirect()
            ->route('calender.index', ['date' => $validated['due_date']])
            ->with('success', 'Tugas berhasil ditambahkan ke kalender!');
    }

    /**
     * Show the form for editing the given task.
     */
    public function edit(Request $request, Task $task): View
    {
        $this->ensureTaskBelongsToUser($request, $task);

        // Supaya textarea/langkah lama langsung tampil saat halaman edit dibuka.
        $task->load('subtasks');

        return view('task.edit', [
            'task' => $task,
            'categories' => $this->categoriesFor($request),
        ]);
    }

    /**
     * Update the given task.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->ensureTaskBelongsToUser($request, $task);

        $validated = $this->validateTask($request);

        $task->update([
            'title' => $validated['title'],
            'notes' => $validated['description'] ?? null,
            'date' => $validated['due_date'],
            'deadline_date' => $validated['due_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'priority' => strtoupper($validated['priority']),
        ]);

        $this->syncReminder($task, $validated['reminder_minutes'] ?? null);
        $this->syncSubtasks($task, $validated['subtasks'] ?? []);

        return redirect()
            ->route('task.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Delete the given task.
     */
    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $this->ensureTaskBelongsToUser($request, $task);

        $task->delete();

        return redirect()
            ->route('task.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Flip a task's completion status from the Pantau Tugas page.
     */
    public function toggleComplete(Request $request, Task $task): JsonResponse
    {
        $this->ensureTaskBelongsToUser($request, $task);

        $task->update([
            'is_completed' => ! $task->is_completed,
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $task->is_completed,
        ]);
    }

    /**
     * Flip a subtask's completion status and return the fresh counter.
     */
    public function toggleSubtask(Request $request, Subtask $subtask): JsonResponse
    {
        $task = $subtask->task;

        // Jangan sampai user bisa mencentang subtask milik orang lain.
        abort_unless($task && $task->user_id === $request->user()->id, 404);

        $subtask->update([
            'is_completed' => ! $subtask->is_completed,
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $subtask->is_completed,
            'done' => $task->subtasks()->where('is_completed', true)->count(),
            'total' => $task->subtasks()->count(),
        ]);
    }

    /**
     * Validate the task form input.
     *
     * @return array<string, mixed>
     */
    private function validateTask(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where('user_id', $request->user()->id),
            ],
            'priority' => ['required', Rule::in(['high', 'medium', 'low'])],
            'reminder_minutes' => ['nullable', 'integer', 'min:0'],
            'subtasks' => ['nullable', 'array'],
            'subtasks.*' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Get the authenticated user's categories.
     *
     * @return Collection<int, Category>
     */
    private function categoriesFor(Request $request): Collection
    {
        $user = $request->user();

        // Jika user belum punya kategori sama sekali, otomatis buatkan 3 kategori default DailyPlan
        if ($user->categories()->count() === 0) {
            $user->categories()->createMany([
                ['name' => 'School',       'color_code' => '#5489D6'],
                ['name' => 'Organization', 'color_code' => '#8B68D0'],
                ['name' => 'Personal',     'color_code' => '#62B58A'],
            ]);
        }

        return $user->categories()->orderBy('name')->get();
    }

    /**
     * Abort when the task does not belong to the authenticated user.
     */
    private function ensureTaskBelongsToUser(Request $request, Task $task): void
    {
        abort_unless($task->user_id === $request->user()->id, 404);
    }

    /**
     * Replace the task's reminder with the selected option.
     */
    private function syncReminder(Task $task, ?int $reminderMinutes): void
    {
        $task->reminders()->delete();

        if ($reminderMinutes !== null && $reminderMinutes > 0) {
            $task->reminders()->create([
                'reminder_time' => $reminderMinutes,
            ]);
        }
    }

    private function syncSubtasks(Task $task, array $subtasks): void
    {
        $task->subtasks()->delete();

        foreach ($subtasks as $title) {
            $cleanTitle = trim((string) $title);
            if ($cleanTitle !== '') {
                $task->subtasks()->create([
                    'title' => $cleanTitle,
                    'is_completed' => false,
                ]);
            }
        }
    }
}
