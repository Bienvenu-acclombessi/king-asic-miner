<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Configuration\ProductSlide;
use App\Models\Products\Product;
use App\Models\Products\Brand;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Récupérer les slides actifs
        $productSlides = ProductSlide::with('product.variants.prices', 'product.images')
            ->active()
            ->ordered()
            ->get();

        // Récupérer les 5 produits les plus récents avec leurs relations
        $products = Product::with([
            'variants.prices',
            'collections.group',
            'collections.parent',
            'brand',
            'images'
        ])
        ->where('status', 'published')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return view('client.pages.accueil.index', compact('products', 'productSlides'));
    }

    /**
     * Get the first 10 brands for display
     */
    public function getBrands()
    {
        $brands = Brand::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'slug' => \Illuminate\Support\Str::slug($brand->name),
                ];
            });

        return response()->json([
            'success' => true,
            'brands' => $brands,
        ]);
    }

    /**
     * Get the first 4 products for a specific brand
     */
    public function getProductsByBrand($brandId)
    {
        $products = Product::with([
            'variants.prices',
            'collections.group',
            'collections.parent',
            'brand',
            'images'
        ])
        ->where('status', 'published')
        ->where('brand_id', $brandId)
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();

        $productsHtml = $products->map(function ($product) {
            return view('client.pages.accueil.partials.product_card', compact('product'))->render();
        })->join('');

        return response()->json([
            'success' => true,
            'html' => $productsHtml,
            'count' => $products->count(),
        ]);
    }
}
