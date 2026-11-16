<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_form(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Log in to your account');
    }

    public function test_guest_can_view_registration_form(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Create your account');
    }

    public function test_registration_creates_user_and_authenticates_them(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Taylor Learner',
            'email' => 'TAYLOR@example.com',
            'password' => 'secure-pass-123',
            'password_confirmation' => 'secure-pass-123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Taylor Learner',
            'email' => 'taylor@example.com',
        ]);
    }

    public function test_registration_rejects_an_existing_email(): void
    {
        User::factory()->create(['email' => 'taylor@example.com']);

        $this->post(route('register.store'), [
            'name' => 'Taylor Learner',
            'email' => 'TAYLOR@example.com',
            'password' => 'secure-pass-123',
            'password_confirmation' => 'secure-pass-123',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
        $this->assertGuest();
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Taylor Learner',
            'email' => 'taylor@example.com',
            'password' => 'secure-pass-123',
            'password_confirmation' => 'different-pass-123',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_user_can_log_in_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'taylor@example.com',
            'password' => 'secure-pass-123',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'TAYLOR@example.com',
            'password' => 'secure-pass-123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'taylor@example.com',
            'password' => 'secure-pass-123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'taylor@example.com',
            'password' => 'incorrect-pass',
        ])->assertSessionHasErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_from_the_workspace(): void
    {
        $this->get(route('courses.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_is_redirected_away_from_login_and_registration(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('login'))->assertRedirect(route('dashboard'));
        $this->get(route('register'))->assertRedirect(route('dashboard'));
    }
}
