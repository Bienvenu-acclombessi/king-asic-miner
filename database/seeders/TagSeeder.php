<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Type 1: Shop By Coins
            [
                'type' => ['id' => 1, 'name' => 'Shop By Coins'],
                'tags' => [
                    'Alephium',
                    'Bitcoin',
                    'BCH',
                    'CKB',
                    'Dash',
                    'Doge Miner',
                    'ETC',
                    'Grin',
                    'HNS',
                    'Kadena',
                    'Kaspa',
                    'Lbry',
                    'Litecoin',
                    'Monero',
                    'Siacoin',
                    'Zcash',
                    'Bells',
                    'RXD',
                    'ScPrime',
                    'Blocx',
                    'Decred',
                    'Nexa',
                    'Aleo',
                ]
            ],
            // Type 2: Shop By Algorithms
            [
                'type' => ['id' => 2, 'name' => 'Shop By Algorithms'],
                'tags' => [
                    'Blake2B-Sia',
                    'Blake3',
                    'Cuckatoo32',
                    'Eaglesong',
                    'Equihash',
                    'Ethash',
                    'FPGA',
                    'Handshake',
                    'Kadena',
                    'LBC',
                    'RandomX',
                    'Scrypt',
                    'SHA-256',
                    'X11',
                    'KHeavyHash',
                    'SHA512256d',
                    'Blake256R14',
                    'zkSNARK',
                    'NexaPow',
                ]
            ],
            // Type 3: Parts/Accessories
            [
                'type' => ['id' => 3, 'name' => 'Parts/Accessories'],
                'tags' => [
                    'PDU',
                    'Power Supply',
                    'Power Cord',
                    'Control Board',
                    'Miner Fans',
                    'Pay the Price Difference',
                ]
            ],
        ];

        foreach ($tags as $group) {
            foreach ($group['tags'] as $tagValue) {
                Tag::updateOrCreate(
                    ['value' => $tagValue],
                    [
                        'value' => $tagValue,
                        'meta' => [
                            'type' => $group['type']
                        ]
                    ]
                );
            }
        }
    }
}
