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

        User::factory()->create([
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            // 'role'=>'admin'
        ]);
        // Account::factory(2)->create(['type'=>'expense']);
        // // Account::factory(2)->create(['type'=>'supplier']);
        // Account::factory(2)->create(['type'=>'customer']);
        // Account::factory(2)->create(['type'=>'cash']);
        // Account::factory(3)->create([
        // 'name'=>fake()->randomElement(['alfalah','habib','ubl']),
        // 'type'=>'bank']);
        // // Product::factory(3)->create();
        // $products = [
        //     // KZML Shail D.Form Products
        //     ['name' => 'D.Form', 'type' => '1/2', 'description' => 'Aisha KZML Shail D.Form'],
        //     ['name' => 'D.Form', 'type' => '3/8', 'description' => 'Aisha KZML Shail D.Form'],
        //     ['name' => 'D.Form', 'type' => '5/8', 'description' => 'Aisha KZML Shail D.Form'],
        //     ['name' => 'D.Form', 'type' => '3/8', 'description' => 'Aisha KZML Shail D.Form'],
        //     ['name' => 'D.Form', 'type' => '3/8', 'description' => 'Aisha KZML Shail D.Form'],
        //     ['name' => 'D.Form', 'type' => '3/8', 'description' => 'Aisha KZML Shail D.Form'],
        //     ['name' => 'D.Form', 'type' => '1/8', 'description' => 'Aisha KZML Shail D.Form'],
        //     ['name' => 'D.Form', 'type' => '1', 'description' => 'Aisha KZML Shail D.Form'],

        //     // KZML Shail MSB Products
        //     ['name' => 'MSB', 'type' => '1/4', 'description' => 'Aisha KZML Shail MSB'],
        //     ['name' => 'MSB', 'type' => '1/2', 'description' => 'Aisha KZML Shail MSB'],

        //     // KZML Shail T-Section Products
        //     ['name' => 'T-Section', 'type' => '3/8', 'description' => 'Aisha KZML Shail T-Section'],
        //     ['name' => 'T-Section', 'type' => '3/8', 'description' => 'Aisha KZML Shail T-Section'],
        //     ['name' => 'T-Section', 'type' => '3/8', 'description' => 'Aisha KZML Shail T-Section'],
        //     ['name' => 'T-Section', 'type' => '3/8', 'description' => 'Aisha KZML Shail T-Section'],
        //     ['name' => 'T-Section', 'type' => '1', 'description' => 'Aisha KZML Shail T-Section'],

        //     // Faryed Steel Products
        //     ['name' => 'Angle', 'type' => '', 'description' => 'Aisha Faryed Steel Angle'],
        //     ['name' => 'Plate', 'type' => '', 'description' => 'Aisha Faryed Steel Plate'],
        //     ['name' => 'Sarya', 'type' => '', 'description' => 'Aisha Faryed Steel Sarya'],
        //     ['name' => 'HRC Codar', 'type' => '', 'description' => 'Aisha Faryed Steel HRC Codar'],
        //     ['name' => 'CRC Codar', 'type' => '', 'description' => 'Aisha Faryed Steel CRC Codar'],
        //     ['name' => 'GP Codar', 'type' => '', 'description' => 'Aisha Faryed Steel GP Codar'],
        //     ['name' => 'Pipe', 'type' => '', 'description' => 'Aisha Faryed Steel Pipe'],

        //     // General Products
        //     ['name' => 'GP Codar', 'type' => '', 'description' => 'Aisha GP Codar'],
        //     ['name' => 'Colour Codar', 'type' => '', 'description' => 'Aisha Colour Codar'],
        //     ['name' => 'HRC', 'type' => '', 'description' => 'Aisha HRC'],
        //     ['name' => 'CRC', 'type' => '', 'description' => 'Aisha CRC'],
        //     ['name' => 'Pipe', 'type' => 'U/C', 'description' => 'Aisha Pipe U/C'],
        //     ['name' => 'Angle', 'type' => '', 'description' => 'Aisha Angle'],
        //     ['name' => 'Plate', 'type' => '', 'description' => 'Aisha Plate'],
        // ];
        // foreach ($products as $product) {
        //     Product::create([
        //         'name' => $product['name'],
        //         'type' => $product['type'],
        //         'description' => $product['description'],
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // $accountTypes = [
        //     // KZML Suppliers
        //     ['name' => 'KZML Shail', 'type' => 'Supplier', 'description' => 'KZML Shail - Steel Supplier'],
        //     ['name' => 'KZML SHAIKH', 'type' => 'Supplier', 'description' => 'KZML SHAIKH - Steel Supplier'],

        //     // Faryed Suppliers
        //     ['name' => 'Faryed Steel', 'type' => 'Supplier', 'description' => 'Faryed Steel - Steel Supplier'],

        //     // GP Suppliers
        //     ['name' => 'GP CHADAR', 'type' => 'Supplier', 'description' => 'GP CHADAR - Steel Supplier'],

        //     // Other Suppliers
        //     ['name' => 'FARAUQUE STEEL', 'type' => 'Supplier', 'description' => 'FARAUQUE STEEL - Steel Supplier'],
        // ];
        // foreach ($accountTypes as $accountType) {
        //     Account::create([
        //         'name' => $accountType['name'],
        //         'type' => strtolower($accountType['type']),
        //         'opening_balance' => 10000,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);

        // }

    }
}
