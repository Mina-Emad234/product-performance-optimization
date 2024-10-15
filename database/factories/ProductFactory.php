<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'color' => fake()->colorName(),
            'kilometers' => fake()->numberBetween(0, 10000),
            'price' => fake()->numberBetween(150000, 100000000),
            'provider_id' => fake()->uuid(),
        ];
    }
}
