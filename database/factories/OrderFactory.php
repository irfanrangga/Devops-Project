<?php

namespace Database\Factories;

use App\Models\Order;
use App\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'number' => $this->faker->unique()->numerify('ORD-#####'),
            'total_price' => $this->faker->randomFloat(2, 10000, 500000),
            'payment_status' => $this->faker->randomElement([PaymentStatus::UNPAID->value, PaymentStatus::PAID->value]),
            'order_status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'snap_token' => null,
            'shipping_address' => $this->faker->address(),
            'shipping_cost' => $this->faker->randomFloat(2, 10000, 50000),
        ];
    }
}
