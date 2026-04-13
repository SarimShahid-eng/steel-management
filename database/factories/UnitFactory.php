<?php

namespace Database\Factories;

use App\Models\Plaza;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plaza_id' => Plaza::factory(),
            'unit_number' => fake()->word(),
            'floor' => fake()->numberBetween(-10000, 10000),
            'due' => fake()->randomFloat(2, 0, 9999999999999.99),
            'monthly_dues_amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'last_payment_date' => fake()->dateTime(),
        ];
    }
}
