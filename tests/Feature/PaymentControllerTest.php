<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\PaymentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    // TC-11-01 Menguji exception jika ID order tidak valid atau id pengguna tidak sesuai
    public function test_show_return_404_if_user_unauthorized(){
        $userActive = User::factory()->create(['id' => 1]);
        $this->actingAs($userActive);

        $userTwo = User::factory()->create(['id' => 2]);

        $orderUserTwo = Order::factory()->create([
            'id' => 10,
            'user_id' => $userTwo->id,
        ]);

        $responseNonExist = $this->get(route('payment.show', 9999));
        $responseNonExist->assertNotFound();

        $responseUnauthorized = $this->get(route('payment.show', $orderUserTwo->id));
        $responseUnauthorized->assertNotFound();
    }

    // TC-11-02 Menguji pesanan yang ditemukan namun status pembayaran tidak sama dengan 1 (unpaid).
    public function test_show_displays_payment_page_without_changing_status_if_already_paid()
    {
        $user = User::factory()->create(['id' => 1]);
        $this->actingAs($user);

        $order = Order::factory()->create([
            'id' => 3,
            'user_id' => $user->id,
            'payment_status' => PaymentStatus::PAID->value,
            'order_status' => 'processing',
            'created_at' => now()->subHours(1),
        ]);

        $response = $this->get(route('payment.show', $order->id));
        $response->assertStatus(200);
        $response->assertViewIs('payment');
        $response->assertViewHas('order');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::PAID->value,
        ]);
    }

    // TC-11-03 Menguji pesanan yang berstatus unpaid dan masih dalam waktu pembayaran yang ditentukan.
    public function test_show_keeps_status_unpaid_if_within_payment_time()
    {
        $user = User::factory()->create(['id' => 1]);
        $this->actingAs($user);

        $order = Order::factory()->create([
            'id' => 5,
            'user_id' => $user->id,
            'payment_status' => PaymentStatus::UNPAID->value,
            'order_status' => 'pending',
            'created_at' => now()->subHours(5),
        ]);

        $response = $this->get(route('payment.show', $order->id));

        $response->assertStatus(200);
        $response->assertViewIs('payment');
        
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::UNPAID->value,
            'order_status' => 'pending',
        ]);
    }

    // TC-11-04 Menguji pesanan yang berstatus 1(unpaid) namun waktu pembayaran telah melebihi batas yang ditentukan.
    public function test_show_updates_status_to_expired_if_payment_time_exceeded()
    {
        $user = User::factory()->create(['id' => 1]);
        $this->actingAs($user);

        $knownDate = Carbon::create(2026, 5, 11, 12, 0, 0);
        Carbon::setTestNow($knownDate);

        $order = Order::factory()->create([
            'id' => 5,
            'user_id' => $user->id,
            'payment_status' => PaymentStatus::UNPAID->value,
            'order_status' => 'pending',
            'created_at' => now()->subHours(13),
        ]);

        $response = $this->get(route('payment.show', $order->id));
        $response->assertStatus(200);
        $response->assertViewIs('payment');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::EXPIRED->value,
            'order_status' => 'cancelled',
        ]);

        $viewOrder = $response->original->getData()['order'];
        $this->assertEquals(PaymentStatus::EXPIRED->value, $viewOrder->payment_status);
        $this->assertEquals('cancelled', $viewOrder->order_status);

        Carbon::setTestNow();
    }
}
