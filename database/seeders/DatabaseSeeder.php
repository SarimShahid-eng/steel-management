<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'full_name' => 'Test User',
        //     'email' => 'test@example.com',
        //     // 'role'=>'admin'
        // ]);
        Account::factory(5)->create(['type'=>'expense']);
        Account::factory(5)->create(['type'=>'supplier']);
        Account::factory(5)->create(['type'=>'customer']);
        Account::factory(5)->create(['type'=>'cash']);
        Account::factory(5)->create(['type'=>'bank']);
        Product::factory(5)->create();
    }
}
