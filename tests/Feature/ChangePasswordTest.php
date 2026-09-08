<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_change_password(): void
    {
        $response = $this->get(route('password.change'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_change_password_page(): void
    {
        $user = User::factory()->create([
            'user_id' => 'testuser',
            'role' => 'operator',
        ]);

        $response = $this->actingAs($user)->get(route('password.change'));
        $response->assertStatus(200);
        $response->assertSee('Buat Password Baru');
    }

    public function test_user_can_change_own_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'user_id' => 'operator01',
            'password' => Hash::make('oldpassword123'),
            'role' => 'operator',
        ]);

        $response = $this->actingAs($user)->post(route('password.update'), [
            'target_user_id' => 'operator01',
            'current_password' => 'oldpassword123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect(route('password.change'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword456', $user->password));
    }

    public function test_user_cannot_change_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'user_id' => 'operator01',
            'password' => Hash::make('correctpassword'),
            'role' => 'operator',
        ]);

        $response = $this->actingAs($user)->post(route('password.update'), [
            'target_user_id' => 'operator01',
            'current_password' => 'wrongpassword',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_password_must_be_at_least_six_characters_and_confirmed(): void
    {
        $user = User::factory()->create([
            'user_id' => 'operator01',
            'password' => Hash::make('oldpassword123'),
            'role' => 'operator',
        ]);

        $response = $this->actingAs($user)->post(route('password.update'), [
            'target_user_id' => 'operator01',
            'current_password' => 'oldpassword123',
            'password' => '12345',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_admin_can_reset_password_for_another_user_without_their_old_password(): void
    {
        $admin = User::factory()->create([
            'user_id' => 'admin_test',
            'role' => 'admin',
        ]);

        $operator = User::factory()->create([
            'user_id' => 'operator_target',
            'password' => Hash::make('initialpass'),
            'role' => 'operator',
        ]);

        $response = $this->actingAs($admin)->post(route('password.update'), [
            'target_user_id' => 'operator_target',
            'password' => 'brandnewpass789',
            'password_confirmation' => 'brandnewpass789',
        ]);

        $response->assertRedirect(route('password.change'));
        $response->assertSessionHas('success');

        $operator->refresh();
        $this->assertTrue(Hash::check('brandnewpass789', $operator->password));
    }

    public function test_non_admin_cannot_change_password_of_another_user(): void
    {
        $operator1 = User::factory()->create([
            'user_id' => 'op1',
            'role' => 'operator',
        ]);

        $operator2 = User::factory()->create([
            'user_id' => 'op2',
            'role' => 'operator',
        ]);

        $response = $this->actingAs($operator1)->post(route('password.update'), [
            'target_user_id' => 'op2',
            'password' => 'hackedpass123',
            'password_confirmation' => 'hackedpass123',
        ]);

        $response->assertStatus(403);
    }
}
