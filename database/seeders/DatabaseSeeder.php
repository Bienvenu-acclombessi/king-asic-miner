<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        

        // Seed tax system data
        $this->call([
            TaxZoneSeeder::class,
            TaxRateSeeder::class,
            TaxClassSeeder::class,
            TaxRateAmountSeeder::class,
        ]);
    }
}
