<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => 'SKU-' . strtoupper($this->faker->unique()->bothify('????####')),
            'title' => $this->faker->words(3, true),
            'product_name' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug(),
            'story' => $this->faker->paragraph(),
            'feature_callouts' => null,
            'main_image' => null,
            'gallery_images' => null,
            'is_published' => true,
            'discount_percentage' => null,
        ];
    }
}

