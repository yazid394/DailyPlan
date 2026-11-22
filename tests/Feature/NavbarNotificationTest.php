<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavbarNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_navbar_shows_empty_state_when_user_has_no_reminders(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Semua beres! Belum ada notifikasi baru hari ini.', false)
            ->assertSee('0 Aktif')
            ->assertSee('images/icon/DailyPlan-polos.png')
            ->assertDontSee('data-notifikasi-badge');
    }

    public function test_navbar_shows_schedule_deadline_and_sleep_reminders(): void
    {
        $user = User::factory()->create();
        $kategori = $user->categories()->create([
            'name' => 'School',
            'color_code' => '#5489D6',
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Jadwal Hari Ini',
            'date' => today()->toDateString(),
            'start_time' => '09:00:00',
            'priority' => 'HIGH',
            'category_id' => $kategori->id,
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Tanpa Jam',
            'date' => today()->toDateString(),
            'priority' => 'MEDIUM',
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Terlewat',
            'date' => today()->subDays(2)->toDateString(),
            'priority' => 'LOW',
        ]);

        $user->preference()->create([
            'default_sleep_start' => '21:30:00',
            'default_sleep_end' => '04:00:00',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('data-notifikasi-badge')
            ->assertSee('4 Aktif')
            ->assertSee('Tugas Jadwal Hari Ini')
            ->assertSee('Jam 09:00')
            ->assertSee('School')
            ->assertSee('Tugas Tanpa Jam')
            ->assertSee('Deadline Hari Ini')
            ->assertSee('Tugas Terlewat')
            ->assertSee('Terlewat')
            ->assertSee('Pengingat waktu tidur')
            ->assertSee('Mulai tidur pukul 21:30')
            ->assertSee('#DDEBFA')
            ->assertSee('#FCE1E1')
            ->assertSee('#E9E1FA')
            ->assertSee(route('profile.index'), false)
            ->assertSee('Lihat Semua Tugas →', false);
    }

    public function test_navbar_notifications_are_limited_to_six_items(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 8) as $hari) {
            $user->tasks()->create([
                'title' => 'Tugas '.$hari,
                'date' => today()->subDays($hari)->toDateString(),
                'priority' => 'LOW',
            ]);
        }

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('6 Aktif')
            ->assertSee('Tugas 8')
            ->assertSee('Tugas 3')
            ->assertDontSee('Tugas 2')
            ->assertDontSee('Tugas 1');
    }
}
