<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_month_data_contains_only_the_authenticated_users_tasks(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $date = now()->startOfMonth()->addDays(2)->format('Y-m-d');

        $user->tasks()->create([
            'title' => 'Belajar Laravel',
            'date' => $date,
            'start_time' => '19:00:00',
            'priority' => 'HIGH',
        ]);

        $otherUser->tasks()->create([
            'title' => 'Tugas Orang Lain',
            'date' => $date,
            'priority' => 'LOW',
        ]);

        $this->actingAs($user)
            ->get(route('calender.data', ['year' => now()->year, 'month' => now()->month]))
            ->assertOk()
            ->assertJsonPath("events.$date.0.judul", 'Belajar Laravel')
            ->assertJsonMissing(['judul' => 'Tugas Orang Lain']);
    }

    public function test_month_data_merges_recurring_schedules_by_day_of_week(): void
    {
        $user = User::factory()->create();
        $targetDate = now()->startOfMonth()->addDays(3);

        $user->recurringSchedules()->create([
            'title' => 'Senam Pagi',
            'day_of_week' => $targetDate->dayOfWeekIso,
            'start_time' => '07:00:00',
            'end_time' => '08:00:00',
        ]);

        $dateKey = $targetDate->format('Y-m-d');

        $this->actingAs($user)
            ->get(route('calender.data', ['year' => $targetDate->year, 'month' => $targetDate->month]))
            ->assertOk()
            ->assertJsonPath("events.$dateKey.0.judul", 'Senam Pagi')
            ->assertJsonPath("events.$dateKey.0.status", 'recurring');
    }

    public function test_calendar_page_accepts_date_query_and_shows_the_task(): void
    {
        $user = User::factory()->create();
        $date = now()->format('Y-m-d');

        $user->tasks()->create([
            'title' => 'Tugas Dari Form',
            'date' => $date,
            'priority' => 'MEDIUM',
        ]);

        $this->actingAs($user)
            ->get(route('calender.index', ['date' => $date]))
            ->assertOk()
            ->assertSee('Tugas Dari Form');
    }
}
