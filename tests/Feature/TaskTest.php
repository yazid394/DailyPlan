<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_pages_require_authentication(): void
    {
        $this->get(route('task.index'))->assertRedirect(route('login'));
        $this->get(route('task.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_task_index_and_create_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('task.index'))->assertOk();
        $this->actingAs($user)
            ->get(route('task.create'))
            ->assertOk()
            ->assertSee('Buat Tugas Baru');
    }

    public function test_user_can_store_a_task(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('task.store'), [
                'title' => 'Belajar Laravel',
                'description' => 'Pelajari routing dan controller.',
                'due_date' => '2026-11-20',
                'start_time' => '19:00',
                'end_time' => '20:00',
                'priority' => 'high',
                'reminder_minutes' => 30,
                'subtasks' => ['Baca Bab 1', '', 'Buat ringkasan'],
            ])
            ->assertRedirect(route('calender.index', ['date' => '2026-11-20']))
            ->assertSessionHas('success');

        $task = $user->tasks()->sole();

        $this->assertSame('Belajar Laravel', $task->title);
        $this->assertSame('Pelajari routing dan controller.', $task->notes);
        $this->assertSame('2026-11-20', $task->date->format('Y-m-d'));
        $this->assertSame('2026-11-20', $task->deadline_date->format('Y-m-d'));
        $this->assertSame('HIGH', $task->priority);

        $this->assertDatabaseHas('task_reminders', [
            'task_id' => $task->id,
            'reminder_time' => 30,
        ]);

        // Baris kosong dibuang, dua langkah nyata tersimpan.
        $this->assertSame(2, $task->subtasks()->count());
        $this->assertSame(['Baca Bab 1', 'Buat ringkasan'], $task->subtasks()->pluck('title')->all());
    }

    public function test_user_can_update_their_own_task(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Tugas Lama',
            'date' => '2026-11-20',
            'priority' => 'LOW',
        ]);

        $this->actingAs($user)
            ->put(route('task.update', $task), [
                'title' => 'Tugas Baru',
                'due_date' => '2026-11-21',
                'priority' => 'medium',
                'reminder_minutes' => 0,
            ])
            ->assertRedirect(route('task.index'));

        $this->assertSame('Tugas Baru', $task->fresh()->title);
        $this->assertSame('MEDIUM', $task->fresh()->priority);
    }

    public function test_user_cannot_update_another_users_task(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = $owner->tasks()->create([
            'title' => 'Tugas Milik Orang Lain',
            'date' => '2026-11-20',
            'priority' => 'LOW',
        ]);

        $this->actingAs($otherUser)
            ->put(route('task.update', $task), [
                'title' => 'Dicoba Ubah',
                'due_date' => '2026-11-21',
                'priority' => 'high',
            ])
            ->assertNotFound();
    }

    public function test_task_validation_requires_a_title_and_due_date(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('task.store'), [])
            ->assertSessionHasErrors(['title', 'due_date', 'priority']);
    }

    public function test_index_uses_real_database_statistics_and_tasks(): void
    {
        $user = User::factory()->create();
        $today = today()->toDateString();

        $user->tasks()->create([
            'title' => 'Tugas Hari Ini',
            'date' => $today,
            'priority' => 'HIGH',
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Selesai',
            'date' => $today,
            'priority' => 'LOW',
            'is_completed' => true,
        ]);

        $this->actingAs($user)
            ->get(route('task.index'))
            ->assertOk()
            ->assertSee('Tugas Hari Ini')
            ->assertSee('Tugas Selesai')
            ->assertSee('50%');
    }

    public function test_index_badges_follow_real_dates(): void
    {
        $user = User::factory()->create();

        $user->tasks()->create([
            'title' => 'Tugas Terlewat',
            'date' => today()->subDays(3)->toDateString(),
            'priority' => 'LOW',
        ]);

        $user->tasks()->create([
            'title' => 'Tugas Besok',
            'date' => today()->addDay()->toDateString(),
            'priority' => 'HIGH',
        ]);

        $this->actingAs($user)
            ->get(route('task.index'))
            ->assertOk()
            ->assertSee('Terlewat 3 hari')
            ->assertSee('1 hari lagi');
    }

    public function test_user_can_toggle_task_completion_realtime(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Tugas Toggle',
            'date' => today()->toDateString(),
            'priority' => 'MEDIUM',
        ]);

        $this->actingAs($user)
            ->patchJson(route('task.toggle', $task))
            ->assertOk()
            ->assertJson(['success' => true, 'is_completed' => true]);

        $this->assertTrue($task->fresh()->is_completed);

        $this->actingAs($user)
            ->patchJson(route('task.toggle', $task))
            ->assertOk()
            ->assertJson(['success' => true, 'is_completed' => false]);

        $this->assertFalse($task->fresh()->is_completed);
    }

    public function test_user_can_toggle_subtask_and_receive_fresh_counter(): void
    {
        $user = User::factory()->create();
        $task = $user->tasks()->create([
            'title' => 'Tugas Dengan Langkah',
            'date' => today()->toDateString(),
            'priority' => 'MEDIUM',
        ]);

        $langkahPertama = $task->subtasks()->create(['title' => 'Langkah 1']);
        $task->subtasks()->create(['title' => 'Langkah 2']);

        $this->actingAs($user)
            ->patchJson(route('subtask.toggle', $langkahPertama))
            ->assertOk()
            ->assertJson([
                'success' => true,
                'is_completed' => true,
                'done' => 1,
                'total' => 2,
            ]);

        $this->assertTrue($langkahPertama->fresh()->is_completed);
    }

    public function test_user_cannot_toggle_another_users_subtask(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = $owner->tasks()->create([
            'title' => 'Tugas Orang Lain',
            'date' => today()->toDateString(),
            'priority' => 'LOW',
        ]);
        $subtask = $task->subtasks()->create(['title' => 'Langkah rahasia']);

        $this->actingAs($otherUser)
            ->patchJson(route('subtask.toggle', $subtask))
            ->assertNotFound();
    }

    public function test_default_categories_are_seeded_once_when_user_has_none(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('task.create'))->assertOk();

        $this->assertSame(
            ['Organization', 'Personal', 'School'],
            $user->categories()->orderBy('name')->pluck('name')->all()
        );

        // Buka lagi: jangan sampai kategori default dobel.
        $this->actingAs($user)->get(route('task.create'))->assertOk();

        $this->assertSame(3, $user->categories()->count());
    }
}
