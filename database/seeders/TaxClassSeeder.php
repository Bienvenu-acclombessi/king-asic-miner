<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taxes\TaxClass;

class TaxClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxClasses = [
            [
                'name' => 'Standard Rate',
                'default' => true,
            ],
            [
                'name' => 'Reduced Rate',
                'default' => false,
            ],
            [
                'name' => 'Zero Rate',
                'default' => false,
            ],
            [
                'name' => 'Super Reduced Rate',
                'default' => false,
            ],
        ];

        foreach ($taxClasses as $class) {
            TaxClass::create($class);
        }

        $this->command->info('Tax classes seeded successfully!');
    }
}
