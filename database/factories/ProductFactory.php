<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(10),
            'description' => fake()->text(20),
            'type' => fake()->text(5),
            'unit' => fake()->randomElement(["kg"]),
        ];
    }
}
