<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collectionGroups = [
            [
                'name' => 'Mining Equipment',
                'handle' => 'mining-equipment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accessories',
                'handle' => 'accessories',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Parts & Components',
                'handle' => 'parts-components',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\Configuration\CollectionGroup::insert($collectionGroups);
    }
}
