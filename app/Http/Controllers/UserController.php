<?php

namespace App\Http\Controllers;

use App\Models\RecurringSchedule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegister(): View
    {
        return view('register');
    }

    /**
     * Store a newly registered user and their default preferences.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = DB::transaction(function () use ($validated): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                // The User model casts `password` to `hashed`, so it is
                // stored already encrypted and must not be hashed twice.
                'password' => $validated['password'],
            ]);

            $user->preference()->create();
            $user->stat()->create();

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('login');
    }

    /**
     * Attempt to authenticate the given credentials.
     *
     * @throws ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak cocok dengan data kami.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show the authenticated user's dashboard.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today();
        $namaHariIni = $today->format('l'); // Contoh: "Sunday"

        // Tugas yang jatuh hari ini, lengkap dengan kategori dan langkahnya.
        $todayTasks = $user->tasks()
            ->with(['category', 'subtasks'])
            ->whereDate('date', $today->toDateString())
            ->orderBy('is_completed', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Jadwal rutin mingguan yang harinya cocok dengan hari ini.
        // Di database day_of_week itu angka 1-7 (Senin-Minggu), tapi kalau
        // suatu hari isinya teks nama hari, tetap kami terima juga.
        $todayRecurrings = $user->recurringSchedules()
            ->with('category')
            ->get()
            ->filter(function (RecurringSchedule $schedule) use ($today, $namaHariIni): bool {
                return is_numeric($schedule->day_of_week)
                    ? (int) $schedule->day_of_week === $today->dayOfWeekIso
                    : strtolower((string) $schedule->day_of_week) === strtolower($namaHariIni);
            })
            ->sortBy(fn(RecurringSchedule $schedule): string => (string) $schedule->start_time)
            ->values();

        // Maksimal 4 deadline terdekat yang belum selesai.
        $upcomingDeadlines = $user->tasks()
            ->with('category')
            ->where('is_completed', false)
            ->whereDate('date', '>', $today->toDateString())
            ->orderBy('date', 'asc')
            ->limit(4)
            ->get();

        // Angka ringkasan juga dihitung dari database user yang login.
        $stats = [
            'total_today' => $todayTasks->count(),
            'completed_today' => $todayTasks->where('is_completed', true)->count(),
            'urgent_count' => $user->tasks()
                ->where('is_completed', false)
                ->whereDate('date', '<=', $today->copy()->addDays(2)->toDateString())
                ->count(),
        ];

        return view('dashboard', compact(
            'user',
            'todayTasks',
            'todayRecurrings',
            'upcomingDeadlines',
            'stats'
        ));
    }

    /**
     * Show the authenticated user's learning progress for the current week.
     */
    public function progress(Request $request): View
    {
        $user = $request->user();

        // Minggu berjalan: Senin 00:00 s.d. Minggu 23:59:59.
        $awalPekan = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $akhirPekan = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $tugasSelesaiQuery = $user->tasks()
            ->where('is_completed', true)
            ->whereDate('date', '>=', $awalPekan->toDateString())
            ->whereDate('date', '<=', $akhirPekan->toDateString());

        $tugasSelesai = (clone $tugasSelesaiQuery)->count();

        $totalTugas = $user->tasks()->count();
        $deadlineTerpenuhi = $totalTugas === 0
            ? '0%'
            : round($tugasSelesai / $totalTugas * 100) . '%';

        // Index 1 = Senin ... 7 = Minggu, biar urutan grafiknya rapi.
        $jamPerHari = array_fill(1, 7, 0.0);
        foreach ((clone $tugasSelesaiQuery)->get() as $task) {
            if (! $task->date) {
                continue;
            }

            $jamPerHari[$task->date->dayOfWeekIso] += $this->hitungJamBelajar($task);
        }

        $totalJamBelajar = array_sum($jamPerHari);
        $durasiFormatted = $this->formatDurasiBelajar($totalJamBelajar);
        $jamTertinggi = max($jamPerHari);

        $namaHari = [1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $namaHariPendek = [1 => 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

        $weeklyChart = collect(range(1, 7))->map(function (int $hariKe) use ($jamPerHari, $jamTertinggi, $namaHariPendek): array {
            $jam = $jamPerHari[$hariKe];

            return [
                'hari' => $namaHariPendek[$hariKe],
                'jam' => $jam,
                'persen' => $jamTertinggi > 0 ? (int) round($jam / $jamTertinggi * 100) : 0,
                'is_highest' => $jamTertinggi > 0 && $jam === $jamTertinggi,
            ];
        })->all();

        $mostProductive = '-';
        if ($jamTertinggi > 0) {
            $hariTerbaik = array_search($jamTertinggi, $jamPerHari, true);
            $mostProductive = $namaHari[$hariTerbaik];
        }

        $targetJam = 20;
        $targetPersen = min(100, (int) round($totalJamBelajar / $targetJam * 100));

        $completedHistory = (clone $tugasSelesaiQuery)
            ->with('category')
            ->latest('date')
            ->latest('id')
            ->limit(5)
            ->get();

        // Distribusi tugas per kategori untuk doughnut chart.
        $kategoriDenganJumlahTugas = $user->categories()->withCount('tasks')->get();

        $categoryChart = [
            'labels' => $kategoriDenganJumlahTugas->pluck('name')->all(),
            'counts' => $kategoriDenganJumlahTugas->pluck('tasks_count')->all(),
            'colors' => $kategoriDenganJumlahTugas
                ->map(fn($kategori): string => $kategori->color_code ?: '#233D7B')
                ->all(),
        ];

        return view('progress.index', compact(
            'tugasSelesai',
            'deadlineTerpenuhi',
            'totalJamBelajar',
            'durasiFormatted',
            'weeklyChart',
            'mostProductive',
            'targetJam',
            'targetPersen',
            'completedHistory',
            'categoryChart'
        ));
    }

    /**
     * Turn a completed task's time range into study hours.
     * Tasks without a time range simply count as one hour.
     */
    private function hitungJamBelajar(Task $task): float
    {
        if (! $task->start_time || ! $task->end_time) {
            return 1.0;
        }

        $mulai = Carbon::parse($task->start_time);
        $selesai = Carbon::parse($task->end_time);

        // Kalau jam selesai lewat tengah malam, pindahkan ke hari berikutnya.
        if ($selesai->lessThanOrEqualTo($mulai)) {
            $selesai->addDay();
        }

        return round($mulai->floatDiffInHours($selesai), 2);
    }

    /**
     * Render study duration as a plain hours-and-minutes label.
     */
    private function formatDurasiBelajar(float $totalJam): string
    {
        $totalMenit = max(0, (int) round($totalJam * 60));
        $jam = intdiv($totalMenit, 60);
        $menit = $totalMenit % 60;

        if ($jam > 0 && $menit > 0) {
            return "{$jam} jam {$menit} menit";
        }

        if ($jam > 0) {
            return "{$jam} jam";
        }

        return "{$menit} menit";
    }

    public function exportProgressPdf(Request $request)
    {
        $user = $request->user();
        $today = Carbon::now('Asia/Jakarta');
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        // 1. Tugas selesai minggu ini
        $weeklyTasks = $user->tasks()
            ->with('category')
            ->where('is_completed', true)
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->orderBy('date', 'asc')
            ->get();

        $tugasSelesai = $weeklyTasks->count();

        // 2. Total durasi belajar (format jam & menit)
        $totalMinutes = $tugasSelesai * 45; // sesuaikan jika ada kalkulasi durasi real di progress()
        $jamInt = intdiv($totalMinutes, 60);
        $sisaMenit = $totalMinutes % 60;
        $durasiFormatted = match (true) {
            $jamInt > 0 && $sisaMenit > 0 => "{$jamInt} jam {$sisaMenit} menit",
            $jamInt > 0                    => "{$jamInt} jam",
            default                        => "{$sisaMenit} menit",
        };

        // 3. Rincian harian Senin - Minggu
        $daysMap = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $dailyBreakdown = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);
            $countDay = $weeklyTasks->filter(fn($t) => Carbon::parse($t->date)->isSameDay($currentDay))->count();
            $minDay = $countDay * 45;
            $dailyBreakdown[] = [
                'hari'    => $daysMap[$i],
                'tanggal' => $currentDay->translatedFormat('d M Y'),
                'jumlah'  => $countDay,
                'durasi'  => $minDay > 0 ? (intdiv($minDay, 60) > 0 ? intdiv($minDay, 60) . ' jam ' . ($minDay % 60) . ' menit' : ($minDay % 60) . ' menit') : '0 menit',
            ];
        }

        // 4. Distribusi kategori
        $categoryStats = $user->categories()->withCount('tasks')->get();

        $pdf = Pdf::loadView('progress.pdf', [
            'user'            => $user,
            'periode'         => $startOfWeek->translatedFormat('d M') . ' – ' . $endOfWeek->translatedFormat('d M Y'),
            'printedAt'       => $today->translatedFormat('d F Y, H:i') . ' WIB',
            'tugasSelesai'    => $tugasSelesai,
            'durasiFormatted' => $durasiFormatted,
            'dailyBreakdown'  => $dailyBreakdown,
            'categoryStats'   => $categoryStats,
            'weeklyTasks'     => $weeklyTasks,
        ])->setPaper('a4', 'portrait');

        $filename = 'Laporan-Kemajuan-DailyPlan-' . str($user->name)->slug() . '.pdf';
        return $pdf->download($filename);
    }
}
