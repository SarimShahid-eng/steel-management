<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'email' => fake()->safeEmail(),
            'password' => Hash::make('password'),
            'full_name' => fake()->word(),
            'phone_number' => fake()->phoneNumber(),
            // 'role' => fake()->randomElement(["admin","chairman","assistant","member"]),
            // 'plaza_id' => Plaza::factory(),
            // 'unit_id' => Unit::factory(),
        ];
    }
}
