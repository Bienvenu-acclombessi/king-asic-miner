<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Products\Product;
use App\Models\Products\ProductImage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all products
        $products = DB::table('products')->get();

        foreach ($products as $product) {
            $attributeData = json_decode($product->attribute_data, true) ?? [];

            // Extract name (handle multilingual)
            $name = $attributeData['name'] ?? 'Product';
            if (is_array($name)) {
                $name = $name['en'] ?? $name[0] ?? 'Product';
            }

            // Extract slug
            $slug = $attributeData['slug'] ?? \Illuminate\Support\Str::slug($name);

            // Extract descriptions
            $shortDescription = $attributeData['short_description'] ?? null;
            if (is_array($shortDescription)) {
                $shortDescription = $shortDescription['en'] ?? $shortDescription[0] ?? null;
            }

            $description = $attributeData['description'] ?? null;
            if (is_array($description)) {
                $description = $description['en'] ?? $description[0] ?? null;
            }

            // Extract SEO data
            $seo = $attributeData['seo'] ?? [];
            $seoTitle = $seo['title'] ?? null;
            if (is_array($seoTitle)) {
                $seoTitle = $seoTitle['en'] ?? $seoTitle[0] ?? null;
            }

            $seoDescription = $seo['description'] ?? null;
            if (is_array($seoDescription)) {
                $seoDescription = $seoDescription['en'] ?? $seoDescription[0] ?? null;
            }

            $seoKeywords = $seo['keywords'] ?? null;
            if (is_array($seoKeywords)) {
                $seoKeywords = $seoKeywords['en'] ?? $seoKeywords[0] ?? null;
            }

            // Extract images
            $images = $attributeData['images'] ?? [];
            $thumbnail = $images['thumbnail'] ?? null;
            $gallery = $images['gallery'] ?? [];

            // Update product with new columns
            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'name' => $name,
                    'slug' => $slug,
                    'short_description' => $shortDescription,
                    'description' => $description,
                    'seo_title' => $seoTitle,
                    'seo_description' => $seoDescription,
                    'seo_keywords' => $seoKeywords,
                    'thumbnail' => $thumbnail,
                ]);

            // Create product images from gallery
            if (!empty($gallery)) {
                foreach ($gallery as $index => $image) {
                    DB::table('product_images')->insert([
                        'product_id' => $product->id,
                        'path' => $image['path'] ?? '',
                        'position' => $image['position'] ?? $index + 1,
                        'alt_text' => $name,
                        'is_primary' => $index === 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Clean attribute_data - keep only custom_attributes
            $cleanedData = [
                'custom_attributes' => $attributeData['custom_attributes'] ?? [],
            ];

            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'attribute_data' => json_encode($cleanedData),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all products
        $products = DB::table('products')->get();

        foreach ($products as $product) {
            $attributeData = json_decode($product->attribute_data, true) ?? [];

            // Get gallery images
            $galleryImages = DB::table('product_images')
                ->where('product_id', $product->id)
                ->orderBy('position')
                ->get();

            $gallery = [];
            foreach ($galleryImages as $image) {
                $gallery[] = [
                    'path' => $image->path,
                    'position' => $image->position,
                ];
            }

            // Restore full attribute_data structure
            $restoredData = [
                'name' => ['en' => $product->name],
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'seo' => [
                    'title' => $product->seo_title,
                    'description' => $product->seo_description,
                    'keywords' => $product->seo_keywords,
                ],
                'images' => [
                    'thumbnail' => $product->thumbnail,
                    'gallery' => $gallery,
                ],
                'custom_attributes' => $attributeData['custom_attributes'] ?? [],
            ];

            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'attribute_data' => json_encode($restoredData),
                ]);
        }

        // Delete all product images
        DB::table('product_images')->truncate();
    }
};
