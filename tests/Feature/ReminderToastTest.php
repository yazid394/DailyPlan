<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReminderToastTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_pages_do_not_load_the_reminder_toast(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('dailyplan-toast-container');
    }

    public function test_authenticated_pages_receive_todays_reminders_as_clean_json(): void
    {
        $user = User::factory()->create();
        $kategori = $user->categories()->create([
            'name' => 'School',
            'color_code' => '#5489D6',
        ]);

        $tugas = $user->tasks()->create([
            'title' => 'Belajar Laravel',
            'date' => today()->toDateString(),
            'start_time' => '09:00:00',
            'priority' => 'HIGH',
            'category_id' => $kategori->id,
        ]);
        $tugas->reminders()->create(['reminder_time' => 30]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('dailyplan-toast-container')
            ->assertSee('"title":"Belajar Laravel"', false)
            ->assertSee('"category":"School"', false)
            ->assertSee('"start_time":"09:00"', false)
            ->assertSee('"reminder_minutes":30', false)
            ->assertSee('dailyplan_reminded_', false)
            ->assertSee('setInterval', false)
            ->assertSee('Mengerti');
    }

    public function test_reminder_minutes_default_to_fifteen_and_irrelevant_tasks_are_skipped(): void
    {
        $user = User::factory()->create();

        $user->tasks()->create([
            'title' => 'Tugas Tanpa Pengingat',
            'date' => today()->toDateString(),
            'start_time' => '14:30:00',
            'priority' => 'MEDIUM',
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Tanpa Jam',
            'date' => today()->toDateString(),
            'priority' => 'LOW',
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Selesai',
            'date' => today()->toDateString(),
            'start_time' => '08:00:00',
            'priority' => 'LOW',
            'is_completed' => true,
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Besok',
            'date' => today()->addDay()->toDateString(),
            'start_time' => '08:00:00',
            'priority' => 'LOW',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('"reminder_minutes":15', false)
            ->assertSee('Tugas Tanpa Pengingat')
            ->assertDontSee('"title":"Tugas Tanpa Jam"', false)
            ->assertDontSee('"title":"Tugas Selesai"', false)
            ->assertDontSee('"title":"Tugas Besok"', false);
    }

    public function test_application_uses_jakarta_timezone(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', now()->getTimezone()->getName());
    }
}
