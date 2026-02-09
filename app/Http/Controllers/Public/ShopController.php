<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Collection;
use App\Models\Configuration\Tag;
use App\Models\Products\Brand;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Display shop page with all products
     */
    public function index(Request $request)
    {
        // Récupérer tous les produits publiés avec leurs relations
        $query = Product::with(['variants.prices', 'collections', 'brand', 'images'])
            ->where('status', 'published');

        // Appliquer les filtres et pagination
        $products = $this->applyFiltersAndPagination($query, $request);

        return view('client.pages.shop.index', compact('products'));
    }

    /**
     * Display category view
     */
    public function categoryView($slug, Request $request)
    {
        // Pour l'instant, on passe juste le slug
        // TODO: Implémenter la logique de filtrage par catégorie
        return view('client.pages.shop.category_view', compact('slug'));
    }

    /**
     * Filter products by tag
     */
    public function filterByTag($slug, Request $request)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $query = Product::with(['variants.prices', 'collections', 'brand', 'images'])
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->where('status', 'published');

        // Appliquer les filtres et pagination
        $products = $this->applyFiltersAndPagination($query, $request);

        $filterType = 'tag';
        $filterValue = $tag->value;

        return view('client.pages.shop.index', compact('products', 'filterType', 'filterValue'));
    }

    /**
     * Filter products by collection
     */
    public function filterByCollection($slug, Request $request)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();

        $query = Product::with(['variants.prices', 'collections', 'brand', 'images'])
            ->whereHas('collections', function($q) use ($collection) {
                $q->where('collections.id', $collection->id);
            })
            ->where('status', 'published');

        // Appliquer les filtres et pagination
        $products = $this->applyFiltersAndPagination($query, $request);

        $filterType = 'collection';
        $filterValue = $collection->attribute_data['name'] ?? 'Collection';

        return view('client.pages.shop.index', compact('products', 'filterType', 'filterValue'));
    }

    /**
     * Filter products by brand
     */
    public function filterByBrand($brandId, Request $request)
    {
        $brand = Brand::findOrFail($brandId);

        $query = Product::with(['variants.prices', 'collections', 'brand', 'images'])
            ->where('brand_id', $brand->id)
            ->where('status', 'published');

        // Appliquer les filtres et pagination
        $products = $this->applyFiltersAndPagination($query, $request);

        $filterType = 'brand';
        $filterValue = $brand->name;

        return view('client.pages.shop.index', compact('products', 'filterType', 'filterValue'));
    }

    /**
     * Apply filters and pagination to product query
     */
    private function applyFiltersAndPagination($query, Request $request)
    {
        // Récupérer les paramètres de l'URL
        $sort = $request->get('sort', 'featured');
        $perPage = $request->get('per_page', 50);

        // Valider per_page
        if (!in_array($perPage, [10, 20, 30, 50])) {
            $perPage = 50;
        }

        // Appliquer le tri
        switch ($sort) {
            case 'price_asc':
                // Tri par prix croissant (utilise le prix minimum des variants)
                $query->addSelect([
                    'min_price' => DB::table('product_variants')
                        ->select(DB::raw('MIN(CAST(JSON_EXTRACT(attribute_data, "$.price") AS DECIMAL(10,2)))'))
                        ->whereColumn('product_variants.product_id', 'products.id')
                        ->whereNull('product_variants.deleted_at')
                        ->limit(1)
                ])
                ->orderBy('min_price', 'asc')
                ->orderBy('created_at', 'desc');
                break;

            case 'price_desc':
                // Tri par prix décroissant (utilise le prix maximum des variants)
                $query->addSelect([
                    'max_price' => DB::table('product_variants')
                        ->select(DB::raw('MAX(CAST(JSON_EXTRACT(attribute_data, "$.price") AS DECIMAL(10,2)))'))
                        ->whereColumn('product_variants.product_id', 'products.id')
                        ->whereNull('product_variants.deleted_at')
                        ->limit(1)
                ])
                ->orderBy('max_price', 'desc')
                ->orderBy('created_at', 'desc');
                break;

            case 'date':
                // Tri par date (plus récent d'abord)
                $query->orderBy('created_at', 'desc');
                break;

            case 'rating':
                // Tri par note (à implémenter quand le système de notation sera en place)
                // Pour l'instant, on trie par date
                $query->orderBy('created_at', 'desc');
                break;

            case 'featured':
            default:
                // Tri par featured d'abord, puis par date
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        // Paginer les résultats et conserver les paramètres de l'URL
        return $query->paginate($perPage)->appends($request->except('page'));
    }
}
