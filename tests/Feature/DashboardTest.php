<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_dashboard_shows_real_tasks_recurrings_and_deadlines(): void
    {
        $user = User::factory()->create(['name' => 'Yazid Diansyah']);
        $today = today();
        $besok = today()->addDay();

        $user->tasks()->create([
            'title' => 'Tugas Hari Ini',
            'date' => $today->toDateString(),
            'start_time' => '09:00:00',
            'priority' => 'HIGH',
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Selesai Hari Ini',
            'date' => $today->toDateString(),
            'start_time' => '10:00:00',
            'priority' => 'LOW',
            'is_completed' => true,
        ]);

        $user->tasks()->create([
            'title' => 'Deadline Besok',
            'date' => $besok->toDateString(),
            'priority' => 'MEDIUM',
        ]);

        $user->recurringSchedules()->create([
            'title' => 'Senam Pagi',
            'day_of_week' => $today->dayOfWeekIso,
            'start_time' => '07:00:00',
            'end_time' => '08:00:00',
        ]);

        $pemilikLain = User::factory()->create();
        $pemilikLain->tasks()->create([
            'title' => 'Tugas Rahasia Orang Lain',
            'date' => $today->toDateString(),
            'priority' => 'HIGH',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Yazid')
            ->assertSee('Tugas Hari Ini')
            ->assertSee('Tugas Selesai Hari Ini')
            ->assertSee('Senam Pagi')
            ->assertSee('Deadline Besok')
            ->assertSee('50%')
            ->assertDontSee('Tugas Rahasia Orang Lain');
    }

    public function test_dashboard_todo_menu_has_real_quick_actions_for_each_task(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Tugas Dengan Menu',
            'date' => today()->toDateString(),
            'priority' => 'MEDIUM',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('toggleTodoMenu(event, '.$task->id.')', false)
            ->assertSee('todo-dropdown-'.$task->id)
            ->assertSee(route('task.edit', $task), false)
            ->assertSee(route('task.index'), false)
            ->assertSee(route('task.destroy', $task), false)
            ->assertSee('Edit Tugas')
            ->assertSee('Lihat Detail / Pantau Tugas')
            ->assertSee('Yakin ingin menghapus tugas ini?', false);
    }
}
