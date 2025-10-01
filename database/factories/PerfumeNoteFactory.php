<?php

namespace Database\Factories;

use App\Models\PerfumeNote;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerfumeNote>
 */
class PerfumeNoteFactory extends Factory
{
    protected $model = PerfumeNote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'note_type' => $this->faker->randomElement(['top', 'middle', 'base']),
            'note_name' => $this->faker->randomElement([
                'Bergamot', 'Lemon', 'Orange', 'Grapefruit', 'Lavender', 'Rose', 'Jasmine', 
                'Sandalwood', 'Vanilla', 'Musk', 'Amber', 'Patchouli'
            ]),
            'description' => $this->faker->sentence(),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}

