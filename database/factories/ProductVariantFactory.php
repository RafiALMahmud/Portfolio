<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => $this->faker->randomElement(['50ml', '100ml', '150ml', '200ml']),
            'size' => $this->faker->randomElement(['50ml', '100ml', '150ml', '200ml']),
            'concentration' => $this->faker->randomElement(['EDT', 'EDP', 'Parfum']),
            'price' => $this->faker->randomFloat(2, 20, 200),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}

