<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_requires_authentication(): void
    {
        $this->get(route('profile.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_their_profile(): void
    {
        $user = User::factory()->create(['name' => 'Yazid Diansyah']);
        $user->preference()->create();
        $user->stat()->create();

        $this->actingAs($user)
            ->get(route('profile.index'))
            ->assertOk()
            ->assertSee('Profil & Preferensi Tidur', false)
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertSee('Pelajar produktif')
            ->assertSee('YD')
            ->assertSee('autocomplete="new-password"', false)
            ->assertDontSee('Foto Profil')
            ->assertDontSee('Gunakan inisial namamu sebagai identitas.');
    }

    public function test_user_can_update_their_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Nama Baru',
                'email' => 'baru@example.com',
            ])
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('success');

        $this->assertSame('Nama Baru', $user->fresh()->name);
        $this->assertSame('baru@example.com', $user->fresh()->email);
    }

    public function test_user_can_keep_their_own_email_when_updating_profile(): void
    {
        $user = User::factory()->create(['email' => 'saya@example.com']);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Nama Baru',
                'email' => 'saya@example.com',
            ])
            ->assertRedirect(route('profile.index'))
            ->assertSessionHasNoErrors();

        $this->assertSame('Nama Baru', $user->fresh()->name);
        $this->assertSame('saya@example.com', $user->fresh()->email);
    }

    public function test_user_can_update_their_sleep_preferences(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('profile.preferences.update'), [
                'default_sleep_start' => '22:30',
                'default_sleep_end' => '05:30',
            ])
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'default_sleep_start' => '22:30',
            'default_sleep_end' => '05:30',
        ]);
    }

    public function test_initials_are_built_from_the_first_two_words(): void
    {
        $this->assertSame('YD', (new User(['name' => 'Yazid Diansyah']))->initials);
        $this->assertSame('YD', (new User(['name' => 'yazid   diansyah putra']))->initials);
        $this->assertSame('RI', (new User(['name' => 'Ridaa']))->initials);
        $this->assertSame('U', (new User(['name' => '   ']))->initials);
    }
}
