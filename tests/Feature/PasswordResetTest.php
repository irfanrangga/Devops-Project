<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test case 1: Halaman forgot-password dapat diakses
     */
    public function test_forgot_password_page_can_be_rendered()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Lupa Password?');
    }

    /**
     * Test case 2: Pengguna dapat meminta link reset password
     */
    public function test_user_can_request_password_reset_link()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'user@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'user@example.com',
        ]);
    }

    /**
     * Test case 3: Halaman reset-password dapat diakses dengan token yang valid
     */
    public function test_reset_password_page_can_be_rendered_with_valid_token()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $token = Password::createToken($user);

        $response = $this->get(route('password.reset', ['token' => $token, 'email' => 'user@example.com']));

        $response->assertStatus(200);
        $response->assertSee('Reset Password');
    }

    /**
     * Test case 4: Pengguna dapat melakukan reset password
     */
    public function test_user_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'user@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // Pastikan password baru bisa dicocokkan
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }
}
