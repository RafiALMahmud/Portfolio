<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper($this->faker->bothify('########')),
            'items' => [
                [
                    'variant_id' => 1,
                    'product_name' => $this->faker->words(2, true),
                    'variant_name' => '50ml',
                    'price' => $this->faker->randomFloat(2, 20, 100),
                    'qty' => $this->faker->numberBetween(1, 5),
                ]
            ],
            'subtotal' => $this->faker->randomFloat(2, 50, 500),
            'shipping' => 0,
            'grand_total' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement([
                Order::STATUS_UNCONFIRMED,
                Order::STATUS_PENDING,
                Order::STATUS_RECEIVED,
                Order::STATUS_UNAVAILABLE,
            ]),
            'payment_gateway' => $this->faker->randomElement(['whatsapp', 'facebook', 'cash_on_delivery']),
            'shipping_address' => $this->faker->address(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'admin_notes' => null,
            'status_updated_at' => null,
        ];
    }
}

