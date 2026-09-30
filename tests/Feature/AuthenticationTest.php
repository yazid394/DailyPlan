<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPreference;
use App\Models\UserStat;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $this->get(route('login'))->assertOk();
    }

    public function test_register_page_can_be_rendered(): void
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_new_user_can_register_and_is_persisted_to_the_database(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Yazid Diansyah',
            'email' => 'yazid@example.com',
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'yazid@example.com')->sole();

        $this->assertSame('Yazid Diansyah', $user->name);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_creates_the_default_preference_and_stat_rows(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Yazid Diansyah',
            'email' => 'yazid@example.com',
            'password' => 'rahasia123',
        ]);

        $user = User::where('email', 'yazid@example.com')->sole();

        $this->assertDatabaseHas('user_preferences', ['user_id' => $user->id]);
        $this->assertDatabaseHas('user_stats', ['user_id' => $user->id]);
        $this->assertSame(0, (int) $user->stat->total_tasks_completed);
    }

    public function test_password_is_stored_hashed_and_never_in_plain_text(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Yazid Diansyah',
            'email' => 'yazid@example.com',
            'password' => 'rahasia123',
        ]);

        $this->assertDatabaseMissing('users', ['password' => 'rahasia123']);
    }

    public function test_registration_rejects_a_duplicate_email(): void
    {
        User::factory()->create(['email' => 'ada@example.com']);

        $this->post(route('register.store'), [
            'name' => 'Duplikat',
            'email' => 'ada@example.com',
            'password' => 'rahasia123',
        ])->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', 'ada@example.com')->count());
    }

    public function test_registration_requires_a_password_of_at_least_eight_characters(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Yazid Diansyah',
            'email' => 'yazid@example.com',
            'password' => 'pendek',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'yazid@example.com',
            'password' => 'rahasia123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'yazid@example.com',
            'password' => 'rahasia123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_a_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'yazid@example.com',
            'password' => 'rahasia123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'yazid@example.com',
            'password' => 'salah-sekali',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_cannot_login_with_an_unknown_email(): void
    {
        $this->post(route('login.store'), [
            'email' => 'tidak-ada@example.com',
            'password' => 'rahasia123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_the_dashboard(): void
    {
        $user = User::factory()->create();
        $user->preference()->create();
        $user->stat()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee($user->name);
    }

    public function test_guest_is_redirected_away_from_login_and_register(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('login'))->assertRedirect(route('dashboard'));
        $this->actingAs($user)->get(route('register'))->assertRedirect(route('dashboard'));
    }

    public function test_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_logout_is_not_reachable_with_a_get_request(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/logout')->assertStatus(405);
        $this->assertAuthenticatedAs($user);
    }

    public function test_landing_page_is_publicly_accessible(): void
    {
        $this->get(route('home'))->assertOk();
    }

    public function test_seeded_user_can_log_in(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => User::where('email', 'test@example.com')->sole()->id,
        ]);

        $this->post(route('login.store'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_user_preference_and_stat_defaults_come_from_the_migration(): void
    {
        $user = User::factory()->create();
        $user->preference()->create();
        $user->stat()->create();

        $this->assertSame('21:00:00', $user->preference->default_sleep_start);
        $this->assertSame('04:00:00', $user->preference->default_sleep_end);
        $this->assertInstanceOf(UserPreference::class, $user->preference);
        $this->assertInstanceOf(UserStat::class, $user->stat);
    }
}
