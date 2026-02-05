<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Collection;
use App\Models\Configuration\Tag;
use App\Models\Customers\CustomerGroup;
use App\Models\Products\Attribute;
use App\Models\Products\Brand;
use App\Models\Products\Product;
use App\Models\Products\ProductOption;
use App\Models\Products\ProductType;
use App\Models\Products\ProductVariant;
use App\Models\Products\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['productType', 'brand', 'variants', 'collections']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(attribute_data, '$.name')) LIKE ?", ['%' . $search . '%']);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Collection filter
        if ($request->filled('collection_id')) {
            $query->whereHas('collections', function($q) use ($request) {
                $q->where('collections.id', $request->collection_id);
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get categories (collections) and statuses for filter dropdowns
        $categories = Collection::whereNull('parent_id')->orderBy('_lft')->get();
        $statuses = [
            (object)['id' => 'draft', 'name' => 'Draft'],
            (object)['id' => 'published', 'name' => 'Published'],
            (object)['id' => 'archived', 'name' => 'Archived'],
            (object)['id' => 'out_of_stock', 'name' => 'Out of Stock'],
        ];

        return view('admin.pages.products.index', compact('products', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        // Get all dependencies
        $productTypes = ProductType::all();
        $brands = Brand::all();
        $collections = Collection::whereNull('parent_id')
            ->with('children')
            ->orderBy('_lft')
            ->get();
        $tags = Tag::all();
        $customerGroups = CustomerGroup::all();
        $productOptions = ProductOption::with('values')->get();
        $attributes = Attribute::with('attributeGroup')
            ->where('attribute_type', 'product')
            ->orWhere('attribute_type', 'product_variant')
            ->get();

        return view('admin.pages.products.create', compact(
            'productTypes',
            'brands',
            'collections',
            'tags',
            'customerGroups',
            'productOptions',
            'attributes'
        ));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_type_id' => 'required|exists:product_types,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:draft,published,archived,out_of_stock',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,attribute_data->slug',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',

            // Collections (categories)
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',

            // Tags
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',

            // Customer groups
            'customer_groups' => 'nullable|array',
            'customer_groups.*' => 'exists:customer_groups,id',

            // Product options
            'product_options' => 'nullable|array',
            'product_options.*' => 'exists:product_options,id',

            // Variants (JSON)
            'variants' => 'nullable|json',

            // Attributes (JSON)
            'attributes' => 'nullable|json',

            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Prepare attribute data
            $attributeData = [
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
            ];

            if ($request->filled('short_description')) {
                $attributeData['short_description'] = $request->short_description;
            }

            if ($request->filled('description')) {
                $attributeData['description'] = $request->description;
            }

            // SEO data
            if ($request->filled('meta_title') || $request->filled('meta_description') || $request->filled('meta_keywords')) {
                $attributeData['seo'] = [
                    'title' => $request->meta_title,
                    'description' => $request->meta_description,
                    'keywords' => $request->meta_keywords,
                ];
            }

            // Custom attributes
            if ($request->filled('attributes')) {
                $customAttributes = json_decode($request->attributes, true);
                if ($customAttributes) {
                    $attributeData['custom_attributes'] = $customAttributes;
                }
            }

            // Create product
            $product = Product::create([
                'product_type_id' => $request->product_type_id,
                'brand_id' => $request->brand_id,
                'status' => $request->status,
                'attribute_data' => $attributeData,
            ]);

            // Attach collections
            if ($request->filled('collections')) {
                $collectionsData = [];
                foreach ($request->collections as $index => $collectionId) {
                    $collectionsData[$collectionId] = ['position' => $index + 1];
                }
                $product->collections()->attach($collectionsData);
            }

            // Attach tags (polymorphic)
            if ($request->filled('tags')) {
                foreach ($request->tags as $tagId) {
                    DB::table('taggables')->insert([
                        'tag_id' => $tagId,
                        'taggable_type' => 'product',
                        'taggable_id' => $product->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Attach customer groups
            if ($request->filled('customer_groups')) {
                $customerGroupsData = [];
                foreach ($request->customer_groups as $groupId) {
                    $customerGroupsData[$groupId] = [
                        'purchasable' => true,
                        'visible' => true,
                        'enabled' => true,
                    ];
                }
                $product->customerGroups()->attach($customerGroupsData);
            }

            // Attach product options
            if ($request->filled('product_options')) {
                $optionsData = [];
                foreach ($request->product_options as $index => $optionId) {
                    $optionsData[$optionId] = ['position' => $index + 1];
                }
                $product->productOptions()->attach($optionsData);
            }

            // Create variants
            if ($request->filled('variants')) {
                $variants = json_decode($request->variants, true);
                if ($variants && is_array($variants)) {
                    foreach ($variants as $variantData) {
                        $this->createVariant($product, $variantData);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Create a product variant
     */
    private function createVariant(Product $product, array $data)
    {
        $variantAttributeData = [];

        // Extract custom attributes for variant
        if (isset($data['custom_attributes'])) {
            $variantAttributeData = $data['custom_attributes'];
        }

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'tax_class_id' => $data['tax_class_id'] ?? null,
            'sku' => $data['sku'] ?? '',
            'gtin' => $data['gtin'] ?? null,
            'mpn' => $data['mpn'] ?? null,
            'ean' => $data['ean'] ?? null,
            'length_value' => $data['length_value'] ?? null,
            'length_unit' => $data['length_unit'] ?? 'cm',
            'width_value' => $data['width_value'] ?? null,
            'width_unit' => $data['width_unit'] ?? 'cm',
            'height_value' => $data['height_value'] ?? null,
            'height_unit' => $data['height_unit'] ?? 'cm',
            'weight_value' => $data['weight_value'] ?? null,
            'weight_unit' => $data['weight_unit'] ?? 'kg',
            'volume_value' => $data['volume_value'] ?? null,
            'volume_unit' => $data['volume_unit'] ?? 'l',
            'shippable' => $data['shippable'] ?? true,
            'stock' => $data['stock'] ?? 0,
            'backorder' => $data['backorder'] ?? 0,
            'purchasable' => $data['purchasable'] ?? 'always',
            'quantity_increment' => $data['quantity_increment'] ?? 1,
            'min_quantity' => $data['min_quantity'] ?? 1,
            'attribute_data' => $variantAttributeData,
        ]);

        // Attach option values to variant
        if (isset($data['option_values']) && is_array($data['option_values'])) {
            $variant->values()->attach($data['option_values']);
        }

        // Create prices for each customer group
        if (isset($data['prices']) && is_array($data['prices'])) {
            foreach ($data['prices'] as $priceData) {
                Price::create([
                    'customer_group_id' => $priceData['customer_group_id'] ?? null,
                    'priceable_type' => 'product_variant',
                    'priceable_id' => $variant->id,
                    'price' => $priceData['price'] ?? 0,
                    'compare_price' => $priceData['compare_price'] ?? null,
                    'min_quantity' => $priceData['min_quantity'] ?? 1,
                ]);
            }
        }

        return $variant;
    }

    /**
     * Show the form for editing the specified product (AJAX).
     */
    public function edit($id)
    {
        try {
            $product = Product::with([
                'productType',
                'brand',
                'variants.prices',
                'variants.values',
                'collections',
                'customerGroups',
                'productOptions',
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, $id)
    {
        // Similar to store method
        // TODO: Implement update logic
    }

    /**
     * Remove the specified product.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Check if product has orders
            if ($product->orderLines()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete product with associated orders.');
            }

            // Soft delete
            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
