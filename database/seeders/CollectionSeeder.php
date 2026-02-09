<?php

namespace Database\Seeders;

use App\Models\Configuration\Collection;
use App\Models\Configuration\CollectionGroup;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create the Mining Equipment collection group
        $miningGroup = CollectionGroup::firstOrCreate(
            ['handle' => 'mining-equipment'],
            [
                'name' => 'Mining Equipment',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $collections = [
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Asic Miner',
                    'description' => 'High-performance ASIC miners for cryptocurrency mining',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Solo Miner',
                    'description' => 'Standalone mining solutions',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Bitmain Antminer',
                    'description' => 'Bitmain Antminer series mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'IceRiver',
                    'description' => 'IceRiver mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Whatsminer',
                    'description' => 'Whatsminer series mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Elphapex',
                    'description' => 'Elphapex mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Goldshell',
                    'description' => 'Goldshell mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Jasminer',
                    'description' => 'Jasminer mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Canaan Avalon',
                    'description' => 'Canaan Avalon mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Volcminer',
                    'description' => 'Volcminer mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'BitDeer',
                    'description' => 'BitDeer mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Fluminer',
                    'description' => 'Fluminer mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'iBeLink',
                    'description' => 'iBeLink mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'iPollo',
                    'description' => 'iPollo mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Bombax',
                    'description' => 'Bombax mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'DragonBall',
                    'description' => 'DragonBall mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'ColEngine',
                    'description' => 'ColEngine mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Other Brand',
                    'description' => 'Other mining equipment brands',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => json_encode([
                    'name' => 'Second Hand Miner',
                    'description' => 'Pre-owned and refurbished mining equipment',
                ]),
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Collection::insert($collections);

        // Create Cooling Solution collections with parent-child relationships
        $this->createCoolingSolutions($miningGroup);
    }

    private function createCoolingSolutions($miningGroup): void
    {
        // Create parent: Hydro Cooling
        $hydroCooling = Collection::create([
            'collection_group_id' => $miningGroup->id,
            'type' => 'static',
            'sort' => 'custom',
            'attribute_data' => [
                'name' => 'Hydro Cooling',
                'description' => 'Water-based cooling solutions for mining equipment',
            ],
            'meta' => [
                'type' => 'cooling solution',
            ],
            '_lft' => 0,
            '_rgt' => 0,
            'parent_id' => null,
        ]);

        // Children of Hydro Cooling
        $hydroChildren = [
            ['name' => 'H - Home Use', 'count' => 17],
            ['name' => 'HC - Container Cooling', 'count' => 17],
            ['name' => 'H20 - Integrated Cooling', 'count' => 6],
            ['name' => 'E - Modular Expansion', 'count' => 7],
            ['name' => 'N - Rack Deployment', 'count' => 3],
        ];

        foreach ($hydroChildren as $child) {
            Collection::create([
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => [
                    'name' => $child['name'],
                    'description' => 'Hydro cooling solution',
                    'count' => $child['count'],
                ],
                'meta' => [
                    'type' => 'cooling solution',
                ],
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => $hydroCooling->id,
            ]);
        }

        // Create parent: Immersion Cooling
        $immersionCooling = Collection::create([
            'collection_group_id' => $miningGroup->id,
            'type' => 'static',
            'sort' => 'custom',
            'attribute_data' => [
                'name' => 'Immersion Cooling',
                'description' => 'Immersion-based cooling solutions for mining equipment',
            ],
            'meta' => [
                'type' => 'cooling solution',
            ],
            '_lft' => 0,
            '_rgt' => 0,
            'parent_id' => null,
        ]);

        // Children of Immersion Cooling
        $immersionChildren = [
            ['name' => 'Home Mining', 'count' => 5],
            ['name' => 'Warehouses Mining', 'count' => 4],
            ['name' => 'Container Mining', 'count' => 4],
            ['name' => 'Oil-cooled parts', 'count' => 5],
        ];

        foreach ($immersionChildren as $child) {
            Collection::create([
                'collection_group_id' => $miningGroup->id,
                'type' => 'static',
                'sort' => 'custom',
                'attribute_data' => [
                    'name' => $child['name'],
                    'description' => 'Immersion cooling solution',
                    'count' => $child['count'],
                ],
                'meta' => [
                    'type' => 'cooling solution',
                ],
                '_lft' => 0,
                '_rgt' => 0,
                'parent_id' => $immersionCooling->id,
            ]);
        }

        // Create parent: Power Generation
        Collection::create([
            'collection_group_id' => $miningGroup->id,
            'type' => 'static',
            'sort' => 'custom',
            'attribute_data' => [
                'name' => 'Power Generation',
                'description' => 'Power generation solutions for mining equipment',
            ],
            'meta' => [
                'type' => 'cooling solution',
            ],
            '_lft' => 0,
            '_rgt' => 0,
            'parent_id' => null,
        ]);

        // Create parent: Air Cooling
        Collection::create([
            'collection_group_id' => $miningGroup->id,
            'type' => 'static',
            'sort' => 'custom',
            'attribute_data' => [
                'name' => 'Air Cooling',
                'description' => 'Air-based cooling solutions for mining equipment',
            ],
            'meta' => [
                'type' => 'cooling solution',
            ],
            '_lft' => 0,
            '_rgt' => 0,
            'parent_id' => null,
        ]);
    }
}
