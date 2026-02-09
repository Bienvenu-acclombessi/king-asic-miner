<?php

namespace Database\Seeders;

use App\Models\Products\Attribute;
use App\Models\Products\AttributeGroup;
use Illuminate\Database\Seeder;

class ProductAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get Technical Specifications attribute group
        $techSpecsGroup = AttributeGroup::firstOrCreate(
            ['handle' => 'technical_specifications'],
            [
                'attributable_type' => 'product',
                'name' => ['en' => 'Technical Specifications'],
                'position' => 1,
            ]
        );

        // Create or get General Information attribute group
        $generalGroup = AttributeGroup::firstOrCreate(
            ['handle' => 'general_information'],
            [
                'attributable_type' => 'product',
                'name' => ['en' => 'General Information'],
                'position' => 0,
            ]
        );

        // Define attributes
        $attributes = [
            // General Information
            [
                'name' => ['en' => 'Manufacturer'],
                'handle' => 'manufacturer',
                'attribute_group_id' => $generalGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'general',
                'required' => false,
                'system' => false,
                'position' => 1,
                'description' => ['en' => 'Product manufacturer or brand'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Model'],
                'handle' => 'model',
                'attribute_group_id' => $generalGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'general',
                'required' => false,
                'system' => false,
                'position' => 2,
                'description' => ['en' => 'Product model number or name'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Release'],
                'handle' => 'release',
                'attribute_group_id' => $generalGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'general',
                'required' => false,
                'system' => false,
                'position' => 3,
                'description' => ['en' => 'Product release date (e.g., May 2024)'],
                'configuration' => [],
                'default_value' => null,
            ],

            // Technical Specifications - Physical
            [
                'name' => ['en' => 'Size'],
                'handle' => 'size',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 10,
                'description' => ['en' => 'Product dimensions (e.g., 195 x 290 x 379mm)'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Weight'],
                'handle' => 'weight',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 11,
                'description' => ['en' => 'Product weight (e.g., 13500g or 13.5kg)'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Noise Level'],
                'handle' => 'noise_level',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 12,
                'description' => ['en' => 'Noise level in decibels (e.g., 75db)'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Fans'],
                'handle' => 'fans',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'number',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 13,
                'description' => ['en' => 'Number of cooling fans'],
                'configuration' => [],
                'default_value' => null,
            ],

            // Technical Specifications - Performance
            [
                'name' => ['en' => 'Hashrate'],
                'handle' => 'hashrate',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 20,
                'description' => ['en' => 'Mining hashrate (e.g., 16Gh/s, 110TH/s)'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Power'],
                'handle' => 'power',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 21,
                'description' => ['en' => 'Power consumption (e.g., 3360W)'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Interface'],
                'handle' => 'interface',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'select',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 22,
                'description' => ['en' => 'Network interface type'],
                'configuration' => [
                    'options' => ['Ethernet', 'WiFi', 'Both']
                ],
                'default_value' => 'Ethernet',
            ],

            // Technical Specifications - Operating Conditions
            [
                'name' => ['en' => 'Temperature'],
                'handle' => 'temperature',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 30,
                'description' => ['en' => 'Operating temperature range (e.g., 5 – 45 °C)'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Humidity'],
                'handle' => 'humidity',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 31,
                'description' => ['en' => 'Operating humidity range (e.g., 5 – 95 %)'],
                'configuration' => [],
                'default_value' => null,
            ],

            // Electrical Specifications
            [
                'name' => ['en' => 'Voltage'],
                'handle' => 'voltage',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 32,
                'description' => ['en' => 'Operating voltage (e.g., 380-415V)'],
                'configuration' => [],
                'default_value' => null,
            ],

            // Hydro Cooling Specifications (for hydro miners)
            [
                'name' => ['en' => 'Coolant Flow'],
                'handle' => 'coolant_flow',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 40,
                'description' => ['en' => 'Coolant flow rate in L/min (e.g., 8.0~10.0 L/min)'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Coolant Pressure'],
                'handle' => 'coolant_pressure',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 41,
                'description' => ['en' => 'Coolant pressure in bar (e.g., ≤3.5 bar)'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Coolant PH Value'],
                'handle' => 'coolant_ph_value',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'textarea',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 42,
                'description' => ['en' => 'Acceptable PH values for different coolants'],
                'configuration' => [],
                'default_value' => null,
            ],
            [
                'name' => ['en' => 'Working Coolant'],
                'handle' => 'working_coolant',
                'attribute_group_id' => $techSpecsGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'technical',
                'required' => false,
                'system' => false,
                'position' => 43,
                'description' => ['en' => 'Compatible coolant types (e.g., Antifreeze / Pure water / Deionized water)'],
                'configuration' => [],
                'default_value' => null,
            ],

            // Product Features
            [
                'name' => ['en' => 'PSU Included'],
                'handle' => 'psu_included',
                'attribute_group_id' => $generalGroup->id,
                'attribute_type' => 'product',
                'type' => 'boolean',
                'section' => 'general',
                'required' => false,
                'system' => false,
                'position' => 50,
                'description' => ['en' => 'Does the product include a PSU?'],
                'configuration' => [],
                'default_value' => false,
            ],
            [
                'name' => ['en' => 'Warranty Included'],
                'handle' => 'warranty_included',
                'attribute_group_id' => $generalGroup->id,
                'attribute_type' => 'product',
                'type' => 'boolean',
                'section' => 'general',
                'required' => false,
                'system' => false,
                'position' => 51,
                'description' => ['en' => 'Does the product include manufacturer warranty?'],
                'configuration' => [],
                'default_value' => true,
            ],
            [
                'name' => ['en' => 'Warranty Period'],
                'handle' => 'warranty_period',
                'attribute_group_id' => $generalGroup->id,
                'attribute_type' => 'product',
                'type' => 'text',
                'section' => 'general',
                'required' => false,
                'system' => false,
                'position' => 52,
                'description' => ['en' => 'Warranty period (e.g., 12 months, 6 months)'],
                'configuration' => [],
                'default_value' => null,
            ],
        ];

        // Create attributes
        foreach ($attributes as $attributeData) {
            Attribute::updateOrCreate(
                ['handle' => $attributeData['handle']],
                $attributeData
            );
        }

        $this->command->info('Product attributes seeded successfully!');
    }
}
