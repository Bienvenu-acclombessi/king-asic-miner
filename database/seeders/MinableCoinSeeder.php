<?php

namespace Database\Seeders;

use App\Models\Products\MinableCoin;
use Illuminate\Database\Seeder;

class MinableCoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coins = [
            [
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'logo' => null, // Will be uploaded later via admin
                'algorithm' => 'SHA-256',
                'difficulty' => '1.0590823241980708e+21',
                'block_time' => 600, // 10 minutes
                'block_reward' => 3.125,
                'default_price' => 100000.00,
                'color' => '#F7931A',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'name' => 'Bitcoin Cash',
                'symbol' => 'BCH',
                'logo' => null,
                'algorithm' => 'SHA-256',
                'difficulty' => '5.0e+20',
                'block_time' => 600,
                'block_reward' => 6.25,
                'default_price' => 500.00,
                'color' => '#8DC351',
                'is_active' => true,
                'position' => 2,
            ],
            [
                'name' => 'Litecoin',
                'symbol' => 'LTC',
                'logo' => null,
                'algorithm' => 'Scrypt',
                'difficulty' => '3.5e+7',
                'block_time' => 150, // 2.5 minutes
                'block_reward' => 6.25,
                'default_price' => 100.00,
                'color' => '#345D9D',
                'is_active' => true,
                'position' => 3,
            ],
            [
                'name' => 'Dogecoin',
                'symbol' => 'DOGE',
                'logo' => null,
                'algorithm' => 'Scrypt',
                'difficulty' => '2.5e+7',
                'block_time' => 60, // 1 minute
                'block_reward' => 10000.00,
                'default_price' => 0.15,
                'color' => '#C3A634',
                'is_active' => true,
                'position' => 4,
            ],
            [
                'name' => 'Kaspa',
                'symbol' => 'KAS',
                'logo' => null,
                'algorithm' => 'kHeavyHash',
                'difficulty' => '1.5e+15',
                'block_time' => 1, // 1 second
                'block_reward' => 50.00,
                'default_price' => 0.15,
                'color' => '#70C7BA',
                'is_active' => true,
                'position' => 5,
            ],
            [
                'name' => 'Ethereum Classic',
                'symbol' => 'ETC',
                'logo' => null,
                'algorithm' => 'Ethash',
                'difficulty' => '2.5e+15',
                'block_time' => 13,
                'block_reward' => 2.56,
                'default_price' => 30.00,
                'color' => '#669073',
                'is_active' => true,
                'position' => 6,
            ],
            [
                'name' => 'Dash',
                'symbol' => 'DASH',
                'logo' => null,
                'algorithm' => 'X11',
                'difficulty' => '8.0e+7',
                'block_time' => 150,
                'block_reward' => 2.884,
                'default_price' => 40.00,
                'color' => '#008CE7',
                'is_active' => true,
                'position' => 7,
            ],
            [
                'name' => 'Monero',
                'symbol' => 'XMR',
                'logo' => null,
                'algorithm' => 'RandomX',
                'difficulty' => '3.5e+11',
                'block_time' => 120,
                'block_reward' => 0.6,
                'default_price' => 180.00,
                'color' => '#FF6600',
                'is_active' => true,
                'position' => 8,
            ],
            [
                'name' => 'Zcash',
                'symbol' => 'ZEC',
                'logo' => null,
                'algorithm' => 'Equihash',
                'difficulty' => '5.0e+7',
                'block_time' => 75,
                'block_reward' => 2.5,
                'default_price' => 50.00,
                'color' => '#ECB244',
                'is_active' => true,
                'position' => 9,
            ],
        ];

        foreach ($coins as $coinData) {
            MinableCoin::updateOrCreate(
                ['symbol' => $coinData['symbol']],
                $coinData
            );
        }

        $this->command->info('Minable coins seeded successfully!');
    }
}
