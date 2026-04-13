<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'fathername' => fake()->word(),
            'phone_number' => fake()->phoneNumber(),
            'account_id' => Account::factory(),
        ];
    }
}
