<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'items' => [
                [
                    'variant_id' => 1,
                    'product_name' => $this->faker->words(2, true),
                    'variant_name' => '50ml',
                    'price' => $this->faker->randomFloat(2, 20, 100),
                    'qty' => $this->faker->numberBetween(1, 5),
                ]
            ],
            'grand_total' => $this->faker->randomFloat(2, 20, 500),
        ];
    }
}

