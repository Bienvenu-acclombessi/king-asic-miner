<?php

namespace Database\Seeders;

use App\Models\Configuration\ProductSlide;
use App\Models\Products\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first 5 published products
        $products = Product::where('status', 'published')
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {
            $this->command->warn('No published products found. Please create some products first.');
            return;
        }

        // Sample background images (you can replace these with actual image URLs or paths)
        $backgroundImages = [
            'https://apextomining.com/apexto/uploads/2024/09/Antminer-L9.jpg',
            'https://apextomining.com/apexto/uploads/2024/08/Bitmain-Antminer-S21.jpg',
            'https://apextomining.com/apexto/uploads/2024/08/Bitmain-Antminer-S21-XP-Hydro.jpg',
            'https://apextomining.com/apexto/uploads/2024/08/bitmain-antminer-s21-hydro.jpg',
            'https://apextomining.com/apexto/uploads/2024/04/Volcminer-D1-Mini-Pre.jpg',
        ];

        foreach ($products as $index => $product) {
            ProductSlide::create([
                'product_id' => $product->id,
                'background_image' => null, // You can set null to use default, or provide actual paths
                'is_active' => true,
                'position' => $index + 1,
            ]);
        }

        $this->command->info('Product slides created successfully!');
    }
}
