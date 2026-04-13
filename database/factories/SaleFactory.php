<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Account;
use App\Models\CustomerAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'customer_account_id' => CustomerAccount::factory(),
            'transaction_id' => ::factory(),
            'total_amount' => fake()->randomFloat(2, 0, 9999999999.99),
            'received_amount' => fake()->randomFloat(2, 0, 9999999999.99),
            'remaining_amount' => fake()->randomFloat(2, 0, 9999999999.99),
            'date' => fake()->date(),
            'customer_account_id_id' => Account::factory(),
        ];
    }
}
