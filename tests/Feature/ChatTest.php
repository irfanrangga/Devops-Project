<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guest cannot access chat endpoints.
     */
    public function test_guest_cannot_access_chat_endpoints()
    {
        $responseGet = $this->getJson(route('chat.get'));
        $responseGet->assertStatus(401);

        $responseSend = $this->postJson(route('chat.store'), ['message' => 'Hello']);
        $responseSend->assertStatus(401);
    }

    /**
     * Logged in user can send a message.
     */
    public function test_user_can_send_message()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('chat.store'), [
                'message' => 'Hello Admin',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('chats', [
            'user_id' => $user->id,
            'message' => 'Hello Admin',
            'is_admin' => false,
        ]);
    }

    /**
     * Logged in user can retrieve their messages with the correct JSON structure.
     */
    public function test_user_can_retrieve_messages()
    {
        $user = User::factory()->create();

        // Create some chats
        Chat::create([
            'user_id' => $user->id,
            'message' => 'Hello Admin',
            'is_admin' => false,
        ]);

        Chat::create([
            'user_id' => $user->id,
            'message' => 'Hello Customer',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('chat.get'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'message',
                        'is_admin',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(2, 'data');
    }
}
