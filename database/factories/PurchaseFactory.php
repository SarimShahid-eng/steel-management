<?php

namespace Database\Factories;

use App\Models\;
use App\Models\Account;
use App\Models\SupplierAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'supplier_account_id' => SupplierAccount::factory(),
            'transaction_id' => ::factory(),
            'total_amount' => fake()->randomFloat(2, 0, 9999999999.99),
            'paid_amount' => fake()->randomFloat(2, 0, 9999999999.99),
            'remaining_amount' => fake()->randomFloat(2, 0, 9999999999.99),
            'date' => fake()->date(),
            'supplier_account_id_id' => Account::factory(),
        ];
    }
}
