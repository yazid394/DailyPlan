<?php

namespace App\Providers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Navbar dipakai di hampir semua halaman, jadi pengingatnya disiapkan di sini.
        View::composer('components.navbar', function (ViewContract $view): void {
            $user = Auth::user();

            if ($user === null) {
                $view->with([
                    'notifications' => collect(),
                    'unreadCount' => 0,
                ]);

                return;
            }

            $today = Carbon::today();
            $tanggalHariIni = $today->toDateString();
            $notifications = collect();
            $sudahDimasukkan = [];

            $tugasHariIni = $user->tasks()
                ->with(['category', 'reminders'])
                ->where('is_completed', false)
                ->whereDate('date', $tanggalHariIni)
                ->orderBy('start_time')
                ->get();

            $tugasTerlewat = $user->tasks()
                ->with('category')
                ->where('is_completed', false)
                ->whereDate('date', '<', $tanggalHariIni)
                ->orderBy('date')
                ->get();

            // (a) Pengingat jadwal: tugas hari ini yang punya jam mulai atau reminder.
            foreach ($tugasHariIni as $task) {
                $jamMulai = $task->start_time ? substr((string) $task->start_time, 0, 5) : null;

                if ($jamMulai === null && $task->reminders->isEmpty()) {
                    continue;
                }

                $sudahDimasukkan[$task->id] = true;
                $notifications->push([
                    'id' => 'schedule-'.$task->id,
                    'jenis' => 'schedule',
                    'judul' => $task->title,
                    'subteks' => ($task->category?->name ?? 'Tanpa Kategori').' • '.($jamMulai ?? 'Pengingat aktif'),
                    'label' => $jamMulai ? 'Jam '.$jamMulai : 'Pengingat aktif',
                    'warna' => '#DDEBFA',
                    'link' => route('task.index'),
                    'prioritas' => 2,
                    'urutan' => $jamMulai ?? '23:59',
                ]);
            }

            // (b) Deadline hari ini / terlewat.
            foreach ($tugasHariIni->concat($tugasTerlewat) as $task) {
                // Tugas yang sudah tampil sebagai pengingat jadwal nggak perlu dobel.
                if (isset($sudahDimasukkan[$task->id])) {
                    continue;
                }

                $terlewat = $task->date && $task->date->lt($today);
                $jamMulai = $task->start_time ? substr((string) $task->start_time, 0, 5) : null;

                $notifications->push([
                    'id' => 'deadline-'.$task->id,
                    'jenis' => 'deadline',
                    'judul' => $task->title,
                    'subteks' => ($task->category?->name ?? 'Tanpa Kategori').' • '.($task->date?->format('d M Y') ?? $tanggalHariIni),
                    'label' => $terlewat ? 'Terlewat' : 'Deadline Hari Ini',
                    'warna' => '#FCE1E1',
                    'link' => route('task.index'),
                    'prioritas' => $terlewat ? 0 : 1,
                    'urutan' => $jamMulai ?? '00:00',
                ]);
            }

            // (c) Pengingat tidur. Di tabel user_preferences memang tidak ada
            // kolom flag aktif, jadi jam tidur yang terisi dianggap aktif.
            $preferensi = $user->preference;
            $jamTidur = $preferensi?->default_sleep_start
                ? substr((string) $preferensi->default_sleep_start, 0, 5)
                : null;

            if ($jamTidur !== null) {
                $notifications->push([
                    'id' => 'tidur',
                    'jenis' => 'sleep',
                    'judul' => 'Pengingat waktu tidur',
                    'subteks' => 'Mulai tidur pukul '.$jamTidur,
                    'label' => 'Tidur',
                    'warna' => '#E9E1FA',
                    'link' => route('profile.index'),
                    'prioritas' => 3,
                    'urutan' => '23:59',
                ]);
            }

            $notifications = $notifications
                ->sortBy([
                    ['prioritas', 'asc'],
                    ['urutan', 'asc'],
                ])
                ->values()
                ->take(6);

            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $notifications->count(),
            ]);
        });

        // Data untuk pemantau waktu pengingat toast di seluruh halaman.
        View::composer('components.reminder-toast', function (ViewContract $view): void {
            $user = Auth::user();
            $reminders = [];

            if ($user !== null) {
                $tugasHariIni = $user->tasks()
                    ->with(['category', 'reminders'])
                    ->where('is_completed', false)
                    ->whereDate('date', Carbon::today()->toDateString())
                    ->whereNotNull('start_time')
                    ->orderBy('start_time')
                    ->get();

                $reminders = $tugasHariIni
                    ->map(fn (Task $task): array => [
                        'id' => $task->id,
                        'title' => $task->title,
                        'category' => $task->category?->name ?? 'Tanpa Kategori',
                        'start_time' => substr((string) $task->start_time, 0, 5),
                        'reminder_minutes' => (int) ($task->reminders->first()?->reminder_time ?? 15),
                    ])
                    ->all();
            }

            $view->with('reminders', $reminders);
        });
    }
}
