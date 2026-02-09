<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use App\Models\Products\Attribute;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display product detail page
     */
    public function show($slug)
    {
        // Find product by slug (direct column)
        $product = Product::where('slug', $slug)
            ->with([
                'brand',
                'productType',
                'variants.prices',
                'variants.values.productOption',
                'collections',
                'tags',
                'productOptions.values',
                'minableCoins',
                'images',
                'attributes' => function($query) {
                    $query->orderBy('position');
                }
            ])
            ->firstOrFail();


        // Get related products from same collections or brand
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('status', 'published')
            ->where(function($query) use ($product) {
                if ($product->brand_id) {
                    $query->where('brand_id', $product->brand_id);
                }
                if ($product->collections->isNotEmpty()) {
                    $query->orWhereHas('collections', function($q) use ($product) {
                        $q->whereIn('collections.id', $product->collections->pluck('id'));
                    });
                }
            })
            ->with(['brand', 'variants.prices', 'images'])
            ->limit(5)
            ->get();

        // Get product attributes definitions for display
        $productAttributes = Attribute::where('attribute_type', 'product')
            ->with('attributeGroup')
            ->orderBy('position')
            ->get();
        


        return view('client.pages.product.index', compact('product', 'relatedProducts', 'productAttributes'));
    }
}
