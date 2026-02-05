<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taxes\TaxZone;

class TaxZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxZones = [
            [
                'name' => 'France',
                'zone_type' => 'country',
                'price_display' => 'including_tax',
                'active' => true,
                'default' => true,
            ],
            [
                'name' => 'European Union',
                'zone_type' => 'region',
                'price_display' => 'including_tax',
                'active' => true,
                'default' => false,
            ],
            [
                'name' => 'United States',
                'zone_type' => 'country',
                'price_display' => 'excluding_tax',
                'active' => true,
                'default' => false,
            ],
            [
                'name' => 'United Kingdom',
                'zone_type' => 'country',
                'price_display' => 'including_tax',
                'active' => true,
                'default' => false,
            ],
            [
                'name' => 'Canada',
                'zone_type' => 'country',
                'price_display' => 'excluding_tax',
                'active' => true,
                'default' => false,
            ],
            [
                'name' => 'China',
                'zone_type' => 'country',
                'price_display' => 'including_tax',
                'active' => true,
                'default' => false,
            ],
            [
                'name' => 'Rest of World',
                'zone_type' => 'region',
                'price_display' => 'excluding_tax',
                'active' => true,
                'default' => false,
            ],
        ];

        foreach ($taxZones as $zone) {
            TaxZone::create($zone);
        }

        $this->command->info('Tax zones seeded successfully!');
    }
}
