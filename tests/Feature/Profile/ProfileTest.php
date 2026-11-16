<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Personal information')
            ->assertSee('Change password')
            ->assertSee($user->email);
    }

    public function test_authenticated_user_can_update_name_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'UPDATED@example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Your profile has been updated.');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_profile_update_rejects_an_email_used_by_another_user(): void
    {
        $user = User::factory()->create(['email' => 'original@example.com']);
        User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'taken@example.com',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => 'original@example.com',
        ]);
    }

    public function test_authenticated_user_can_change_password_with_their_current_password(): void
    {
        $user = User::factory()->create(['password' => 'old-password-123']);

        $response = $this->actingAs($user)->patch(route('profile.password.update'), [
            'current_password' => 'old-password-123',
            'password' => 'new-password-456',
            'password_confirmation' => 'new-password-456',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('password_success', 'Your password has been updated.');
        $this->assertTrue(Hash::check('new-password-456', $user->fresh()->password));
    }

    public function test_password_change_rejects_an_incorrect_current_password(): void
    {
        $user = User::factory()->create(['password' => 'old-password-123']);

        $this->actingAs($user)->patch(route('profile.password.update'), [
            'current_password' => 'incorrect-password',
            'password' => 'new-password-456',
            'password_confirmation' => 'new-password-456',
        ])->assertSessionHasErrors([
            'current_password' => 'The current password is incorrect.',
        ]);

        $this->assertTrue(Hash::check('old-password-123', $user->fresh()->password));
    }

    public function test_guest_is_redirected_to_login_from_profile_settings(): void
    {
        $this->get(route('profile.edit'))
            ->assertRedirect(route('login'));
    }
}
