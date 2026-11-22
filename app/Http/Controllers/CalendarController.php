<?php

namespace App\Http\Controllers;

use App\Models\RecurringSchedule;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $dataBulanIni = $this->getMonthScheduleData($now->year, $now->month);

        return view('calender.index', [
            'initialEvents' => $dataBulanIni['events'],
            'initialDots' => $dataBulanIni['dots'],
        ]);
    }

    // Endpoint JSON saat user menggeser bulan (‹ atau ›)
    public function fetchMonthData(Request $request)
    {
        $year = (int) $request->query('year', Carbon::now()->year);
        $month = (int) $request->query('month', Carbon::now()->month);

        return response()->json($this->getMonthScheduleData($year, $month));
    }

    private function getMonthScheduleData(int $year, int $month): array
    {
        $userId = Auth::id();

        // 1. Ambil tugas sekali jalan dari tabel `tasks` pada bulan tersebut.
        // whereYear/whereMonth bekerja langsung pada kolom tanggal, jadi aman
        // meski `date` di-cast menjadi objek Carbon oleh model Task.
        $tasks = Task::with('category')
            ->where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('start_time', 'asc')
            ->get();

        // 2. Ambil semua jadwal rutin mingguan dari `recurring_schedules`
        $recurrings = RecurringSchedule::with('category')
            ->where('user_id', $userId)
            ->get();

        $eventsByDate = [];
        $dotsByDate = [];

        // Mapping warna kartu & titik berdasarkan nama kategori
        $colorMap = [
            'School' => ['bg' => '#DDEBFA', 'dot' => '#5489D6'],
            'Organization' => ['bg' => '#E9E1FA', 'dot' => '#8B68D0'],
            'Personal' => ['bg' => '#DDF3E6', 'dot' => '#62B58A'],
        ];

        // Loop setiap tanggal dari tanggal 1 sampai akhir bulan
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($year, $month, $day);
            $dateKey = $currentDate->format('Y-m-d'); // Contoh: "2026-11-21"
            $dayName = $currentDate->format('l');     // Contoh: "Saturday"

            $dailyItems = [];
            $dailyDots = [];

            // A. Masukkan jadwal rutin yang harinya cocok.
            // day_of_week disimpan sebagai angka 1-7 (Senin-Minggu), tapi tetap
            // terima nama hari bila suatu hari datanya berbentuk teks.
            foreach ($recurrings as $rec) {
                $matchesDay = is_numeric($rec->day_of_week)
                    ? (int) $rec->day_of_week === $currentDate->dayOfWeekIso
                    : strtolower((string) $rec->day_of_week) === strtolower($dayName);

                if ($matchesDay) {
                    $catName = $rec->category->name ?? 'School';
                    $colors = $colorMap[$catName] ?? ['bg' => '#FBE2A8', 'dot' => '#EAA549'];

                    $dailyItems[] = [
                        'waktu' => $rec->start_time ? Carbon::parse($rec->start_time)->format('H:i') : 'Rutin',
                        'judul' => $rec->title,
                        'kategori' => $catName.' • Rutin',
                        'bg' => $colors['bg'],
                        'status' => 'recurring',
                    ];

                    if (! in_array($colors['dot'], $dailyDots)) {
                        $dailyDots[] = $colors['dot'];
                    }
                }
            }

            // B. Masukkan tugas dari tabel `tasks` yang jatuh pada tanggal ini.
            // `$task->date` sudah berupa Carbon, jadi formatkan ke string Y-m-d
            // sebelum dibandingkan dengan $dateKey.
            foreach ($tasks as $task) {
                if (Carbon::parse($task->date)->format('Y-m-d') !== $dateKey) {
                    continue;
                }

                $catName = $task->category->name ?? 'School';
                $colors = $colorMap[$catName] ?? ['bg' => '#FBE2A8', 'dot' => '#EAA549'];

                $dailyItems[] = [
                    'waktu' => $task->start_time ? Carbon::parse($task->start_time)->format('H:i') : 'Deadline',
                    'judul' => $task->title,
                    'kategori' => $catName,
                    'bg' => $colors['bg'],
                    'status' => $task->is_completed ? 'completed' : 'pending',
                ];

                if (! in_array($colors['dot'], $dailyDots)) {
                    $dailyDots[] = $colors['dot'];
                }
            }

            // Urutkan kegiatan harian berdasarkan jam paling pagi
            usort($dailyItems, fn ($a, $b) => strcmp($a['waktu'], $b['waktu']));

            if (! empty($dailyItems)) {
                $eventsByDate[$dateKey] = $dailyItems;
                $dotsByDate[$dateKey] = $dailyDots;
            }
        }

        return [
            'events' => $eventsByDate,
            'dots' => $dotsByDate,
        ];
    }
}
