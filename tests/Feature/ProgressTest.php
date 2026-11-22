<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_progress_page_requires_authentication(): void
    {
        $this->get(route('progress.index'))->assertRedirect(route('login'));
    }

    public function test_progress_uses_real_database_metrics_and_weekly_chart(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $kategori = $user->categories()->create([
            'name' => 'School',
            'color_code' => '#5489D6',
        ]);

        $senin = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $rabu = $senin->copy()->addDays(2);

        // Senin: 2 jam, lengkap dengan jam mulai dan selesai.
        $user->tasks()->create([
            'title' => 'Belajar Laravel',
            'date' => $senin->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'priority' => 'HIGH',
            'is_completed' => true,
            'category_id' => $kategori->id,
        ]);

        // Rabu: 1,5 jam + 1 jam tanpa jam diisi = 2,5 jam (hari paling produktif).
        $user->tasks()->create([
            'title' => 'Latihan Database',
            'date' => $rabu->toDateString(),
            'start_time' => '13:00:00',
            'end_time' => '14:30:00',
            'priority' => 'MEDIUM',
            'is_completed' => true,
        ]);

        $user->tasks()->create([
            'title' => 'Baca Modul',
            'date' => $rabu->toDateString(),
            'priority' => 'LOW',
            'is_completed' => true,
        ]);

        // Tugas belum selesai: tidak boleh masuk hitungan jam.
        $user->tasks()->create([
            'title' => 'Tugas Pending',
            'date' => Carbon::now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'priority' => 'HIGH',
        ]);

        // Tugas minggu lalu dan tugas user lain juga tidak boleh ikut.
        $user->tasks()->create([
            'title' => 'Tugas Minggu Lalu',
            'date' => $senin->copy()->subDay()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'priority' => 'HIGH',
            'is_completed' => true,
        ]);

        $otherUser->tasks()->create([
            'title' => 'Tugas Orang Lain',
            'date' => $rabu->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'priority' => 'HIGH',
            'is_completed' => true,
        ]);

        $response = $this->actingAs($user)->get(route('progress.index'));

        $response->assertOk()
            ->assertViewHas('tugasSelesai', 3)
            ->assertViewHas('deadlineTerpenuhi', '60%')
            ->assertViewHas('totalJamBelajar', 4.5)
            ->assertViewHas('durasiFormatted', '4 jam 30 menit')
            ->assertSee('4 jam 30 menit')
            ->assertViewHas('mostProductive', 'Rabu')
            ->assertViewHas('targetJam', 20)
            ->assertViewHas('targetPersen', 23)
            ->assertViewHas('categoryChart', function (array $chart): bool {
                return $chart['labels'] === ['School']
                    && $chart['counts'] === [1]
                    && $chart['colors'] === ['#5489D6'];
            })
            ->assertSee('https://cdn.jsdelivr.net/npm/chart.js', false)
            ->assertSee('weeklyStudyChart')
            ->assertSee('categoryDoughnutChart')
            ->assertSee('Jam Belajar Mingguan')
            ->assertSee('Distribusi Tugas per Kategori')
            ->assertSee('Belajar Laravel')
            ->assertSee('Latihan Database')
            ->assertSee('School')
            ->assertDontSee('Tugas Minggu Lalu')
            ->assertDontSee('Tugas Orang Lain');

        $chart = $response->viewData('weeklyChart');

        $this->assertCount(7, $chart);
        $this->assertSame(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'], array_column($chart, 'hari'));
        $this->assertSame(100, $chart[2]['persen']);
        $this->assertTrue($chart[2]['is_highest']);
        $this->assertSame(2.5, $chart[2]['jam']);
        $this->assertSame(80, $chart[0]['persen']);
        $this->assertFalse($chart[0]['is_highest']);
        $this->assertSame(0, $chart[6]['persen']);
    }

    public function test_progress_shows_empty_state_when_user_has_no_tasks(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('progress.index'))
            ->assertOk()
            ->assertViewHas('tugasSelesai', 0)
            ->assertViewHas('deadlineTerpenuhi', '0%')
            ->assertViewHas('totalJamBelajar', 0.0)
            ->assertViewHas('durasiFormatted', '0 menit')
            ->assertSee('0 menit')
            ->assertViewHas('mostProductive', '-')
            ->assertViewHas('targetPersen', 0)
            ->assertViewHas('categoryChart', function (array $chart): bool {
                return $chart['labels'] === []
                    && $chart['counts'] === []
                    && $chart['colors'] === [];
            })
            ->assertSee('Belum ada data')
            ->assertSee('#E5E7EB')
            ->assertSee('Belum ada tugas selesai minggu ini.');
    }

    public function test_progress_formats_minutes_only_study_duration(): void
    {
        $user = User::factory()->create();

        $user->tasks()->create([
            'title' => 'Latihan Singkat',
            'date' => Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'priority' => 'LOW',
            'is_completed' => true,
        ]);

        $this->actingAs($user)
            ->get(route('progress.index'))
            ->assertOk()
            ->assertViewHas('totalJamBelajar', 0.75)
            ->assertViewHas('durasiFormatted', '45 menit')
            ->assertSee('45 menit');
    }

    public function test_category_chart_uses_brand_color_when_color_is_empty(): void
    {
        $user = User::factory()->create();
        $user->categories()->create([
            'name' => 'Tanpa Warna',
            'color_code' => '',
        ]);

        $this->actingAs($user)
            ->get(route('progress.index'))
            ->assertOk()
            ->assertViewHas('categoryChart', function (array $chart): bool {
                return $chart['labels'] === ['Tanpa Warna']
                    && $chart['counts'] === [0]
                    && $chart['colors'] === ['#233D7B'];
            });
    }

    public function test_completed_history_only_contains_five_latest_tasks_this_week(): void
    {
        $user = User::factory()->create();
        $senin = Carbon::now()->startOfWeek(Carbon::MONDAY);

        foreach (range(1, 7) as $hariKe) {
            $user->tasks()->create([
                'title' => 'Tugas Selesai '.$hariKe,
                'date' => $senin->copy()->addDays($hariKe - 1)->toDateString(),
                'priority' => 'LOW',
                'is_completed' => true,
            ]);
        }

        $this->actingAs($user)
            ->get(route('progress.index'))
            ->assertOk()
            ->assertViewHas('completedHistory', function ($history): bool {
                return $history->count() === 5
                    && $history->first()->title === 'Tugas Selesai 7'
                    && $history->last()->title === 'Tugas Selesai 3';
            });
    }
}
