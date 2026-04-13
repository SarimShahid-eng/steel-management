<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $initialAmount = fake()->numberBetween(1000, 9000);
        return [
            'name' => fake()->name(),
            'type' => fake()->randomElement(["cash","bank","supplier","customer","expense"]),
            'opening_balance' => $initialAmount,
            'balance' => $initialAmount,
            'balance_type' => fake()->randomElement(["debit","credit"]),
        ];
    }
}
