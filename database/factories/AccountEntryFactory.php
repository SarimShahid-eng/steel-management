<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'account_id' => ::factory(),
            'amount' => fake()->randomFloat(2, 0, 9999999999.99),
            'type' => fake()->randomElement(["debit","credit"]),
        ];
    }
}
