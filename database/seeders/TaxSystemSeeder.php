<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaxSystemSeeder extends Seeder
{
    /**
     * Seed the tax system tables with default data.
     *
     * This seeder can be run independently to populate only the tax-related tables:
     * - Tax Zones (countries/regions)
     * - Tax Rates (specific taxes for each zone)
     * - Tax Classes (standard, reduced, zero, super-reduced)
     * - Tax Rate Amounts (linking classes to rates with percentages)
     *
     * Usage: php artisan db:seed --class=TaxSystemSeeder
     */
    public function run(): void
    {
        $this->command->info('🔄 Seeding Tax System...');
        $this->command->newLine();

        $this->call([
            TaxZoneSeeder::class,
            TaxRateSeeder::class,
            TaxClassSeeder::class,
            TaxRateAmountSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Tax System seeded successfully!');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('   - Tax Zones: 7 zones created (France, EU, US, UK, Canada, China, ROW)');
        $this->command->info('   - Tax Rates: 13 rates created across all zones');
        $this->command->info('   - Tax Classes: 4 classes created (Standard, Reduced, Zero, Super Reduced)');
        $this->command->info('   - Tax Rate Amounts: Multiple rate assignments with real-world percentages');
        $this->command->newLine();
    }
}
