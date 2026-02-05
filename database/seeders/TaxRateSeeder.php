<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taxes\TaxZone;
use App\Models\Taxes\TaxRate;

class TaxRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // France - TVA
        $france = TaxZone::where('name', 'France')->first();
        if ($france) {
            TaxRate::create([
                'tax_zone_id' => $france->id,
                'priority' => 1,
                'name' => 'TVA France',
            ]);
        }

        // European Union - VAT
        $eu = TaxZone::where('name', 'European Union')->first();
        if ($eu) {
            TaxRate::create([
                'tax_zone_id' => $eu->id,
                'priority' => 1,
                'name' => 'VAT EU Standard',
            ]);
            TaxRate::create([
                'tax_zone_id' => $eu->id,
                'priority' => 2,
                'name' => 'VAT EU Reduced',
            ]);
        }

        // United States - Sales Tax
        $us = TaxZone::where('name', 'United States')->first();
        if ($us) {
            TaxRate::create([
                'tax_zone_id' => $us->id,
                'priority' => 1,
                'name' => 'US Sales Tax',
            ]);
            TaxRate::create([
                'tax_zone_id' => $us->id,
                'priority' => 2,
                'name' => 'US State Tax',
            ]);
        }

        // United Kingdom - VAT
        $uk = TaxZone::where('name', 'United Kingdom')->first();
        if ($uk) {
            TaxRate::create([
                'tax_zone_id' => $uk->id,
                'priority' => 1,
                'name' => 'VAT UK',
            ]);
        }

        // Canada - GST/HST
        $canada = TaxZone::where('name', 'Canada')->first();
        if ($canada) {
            TaxRate::create([
                'tax_zone_id' => $canada->id,
                'priority' => 1,
                'name' => 'GST Canada',
            ]);
            TaxRate::create([
                'tax_zone_id' => $canada->id,
                'priority' => 2,
                'name' => 'HST Canada',
            ]);
            TaxRate::create([
                'tax_zone_id' => $canada->id,
                'priority' => 3,
                'name' => 'PST Canada',
            ]);
        }

        // China - VAT
        $china = TaxZone::where('name', 'China')->first();
        if ($china) {
            TaxRate::create([
                'tax_zone_id' => $china->id,
                'priority' => 1,
                'name' => 'VAT China',
            ]);
        }

        // Rest of World
        $row = TaxZone::where('name', 'Rest of World')->first();
        if ($row) {
            TaxRate::create([
                'tax_zone_id' => $row->id,
                'priority' => 1,
                'name' => 'Standard Tax ROW',
            ]);
        }

        $this->command->info('Tax rates seeded successfully!');
    }
}
