<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test case 1: User berhasil melakukan register
     */
    public function test_user_can_successfully_register()
    {
        $response = $this->post(route('register.store'), [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'))
                 ->assertSessionHas('success', 'Akun berhasil dibuat! Silakan login.');

        $this->assertDatabaseHas('users', [
            'email' => 'john@gmail.com',
            'name' => 'John Doe',
        ]);
    }

    /**
     * Test case 2: User gagal register karena email sudah terdaftar
     */
    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->post(route('register.store'), [
            'name' => 'Jane Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
    }
}
