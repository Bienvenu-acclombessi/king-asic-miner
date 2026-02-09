<?php

namespace Database\Seeders;

use App\Models\Products\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Antminer',
            'IceRiver',
            'Elphapex',
            'Whatsminer',
            'Goldshell',
            'Volcminer',
            'Canaan',
            'Jasminer',
            'Solo Miner',
            'Fluminer',
        ];

        foreach ($brands as $brandName) {
            Brand::firstOrCreate(
                ['name' => $brandName]
            );
        }

        $this->command->info('Brands seeded successfully!');
    }
}
