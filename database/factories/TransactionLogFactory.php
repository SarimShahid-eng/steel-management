<?php

namespace Database\Factories;

use App\Models\Plaza;
use App\Models\RelatedResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionLogFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'plaza_id' => Plaza::factory(),
            'transaction_type' => fake()->randomElement(["credit","debit"]),
            'amount' => fake()->randomFloat(2, 0, 9999999999999.99),
            'description' => fake()->text(),
            'balance_before' => fake()->randomFloat(2, 0, 9999999999999.99),
            'balance_after' => fake()->randomFloat(2, 0, 9999999999999.99),
            'recorded_by' => User::factory(),
            'related_resource_type' => fake()->randomElement(["maintenance_post","payment","special_assessment"]),
            'related_resource_id' => RelatedResource::factory(),
        ];
    }
}
