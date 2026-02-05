<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taxes\TaxClass;
use App\Models\Taxes\TaxRate;
use App\Models\Taxes\TaxRateAmount;

class TaxRateAmountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get tax classes
        $standardRate = TaxClass::where('name', 'Standard Rate')->first();
        $reducedRate = TaxClass::where('name', 'Reduced Rate')->first();
        $zeroRate = TaxClass::where('name', 'Zero Rate')->first();
        $superReducedRate = TaxClass::where('name', 'Super Reduced Rate')->first();

        // France - TVA rates
        $tvaFrance = TaxRate::where('name', 'TVA France')->first();
        if ($tvaFrance) {
            if ($standardRate) {
                TaxRateAmount::create([
                    'tax_class_id' => $standardRate->id,
                    'tax_rate_id' => $tvaFrance->id,
                    'percentage' => 20.000, // TVA normale
                ]);
            }
            if ($reducedRate) {
                TaxRateAmount::create([
                    'tax_class_id' => $reducedRate->id,
                    'tax_rate_id' => $tvaFrance->id,
                    'percentage' => 5.500, // TVA réduite
                ]);
            }
            if ($superReducedRate) {
                TaxRateAmount::create([
                    'tax_class_id' => $superReducedRate->id,
                    'tax_rate_id' => $tvaFrance->id,
                    'percentage' => 2.100, // TVA super réduite
                ]);
            }
            if ($zeroRate) {
                TaxRateAmount::create([
                    'tax_class_id' => $zeroRate->id,
                    'tax_rate_id' => $tvaFrance->id,
                    'percentage' => 0.000, // Exonéré
                ]);
            }
        }

        // EU - VAT rates
        $vatEU = TaxRate::where('name', 'VAT EU Standard')->first();
        if ($vatEU && $standardRate) {
            TaxRateAmount::create([
                'tax_class_id' => $standardRate->id,
                'tax_rate_id' => $vatEU->id,
                'percentage' => 21.000, // Average EU VAT
            ]);
        }

        $vatEUReduced = TaxRate::where('name', 'VAT EU Reduced')->first();
        if ($vatEUReduced && $reducedRate) {
            TaxRateAmount::create([
                'tax_class_id' => $reducedRate->id,
                'tax_rate_id' => $vatEUReduced->id,
                'percentage' => 9.000,
            ]);
        }

        // United States - Sales Tax
        $usSalesTax = TaxRate::where('name', 'US Sales Tax')->first();
        if ($usSalesTax && $standardRate) {
            TaxRateAmount::create([
                'tax_class_id' => $standardRate->id,
                'tax_rate_id' => $usSalesTax->id,
                'percentage' => 8.500, // Average US sales tax
            ]);
        }

        // United Kingdom - VAT
        $vatUK = TaxRate::where('name', 'VAT UK')->first();
        if ($vatUK) {
            if ($standardRate) {
                TaxRateAmount::create([
                    'tax_class_id' => $standardRate->id,
                    'tax_rate_id' => $vatUK->id,
                    'percentage' => 20.000,
                ]);
            }
            if ($reducedRate) {
                TaxRateAmount::create([
                    'tax_class_id' => $reducedRate->id,
                    'tax_rate_id' => $vatUK->id,
                    'percentage' => 5.000,
                ]);
            }
            if ($zeroRate) {
                TaxRateAmount::create([
                    'tax_class_id' => $zeroRate->id,
                    'tax_rate_id' => $vatUK->id,
                    'percentage' => 0.000,
                ]);
            }
        }

        // Canada - GST/HST
        $gstCanada = TaxRate::where('name', 'GST Canada')->first();
        if ($gstCanada && $standardRate) {
            TaxRateAmount::create([
                'tax_class_id' => $standardRate->id,
                'tax_rate_id' => $gstCanada->id,
                'percentage' => 5.000, // Federal GST
            ]);
        }

        $hstCanada = TaxRate::where('name', 'HST Canada')->first();
        if ($hstCanada && $standardRate) {
            TaxRateAmount::create([
                'tax_class_id' => $standardRate->id,
                'tax_rate_id' => $hstCanada->id,
                'percentage' => 13.000, // HST (varies by province)
            ]);
        }

        // China - VAT
        $vatChina = TaxRate::where('name', 'VAT China')->first();
        if ($vatChina && $standardRate) {
            TaxRateAmount::create([
                'tax_class_id' => $standardRate->id,
                'tax_rate_id' => $vatChina->id,
                'percentage' => 13.000, // Standard VAT China
            ]);
        }

        // Rest of World
        $rowTax = TaxRate::where('name', 'Standard Tax ROW')->first();
        if ($rowTax && $standardRate) {
            TaxRateAmount::create([
                'tax_class_id' => $standardRate->id,
                'tax_rate_id' => $rowTax->id,
                'percentage' => 10.000, // Generic rate
            ]);
        }

        $this->command->info('Tax rate amounts seeded successfully!');
    }
}
