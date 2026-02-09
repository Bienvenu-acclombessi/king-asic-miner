<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Collection;
use App\Models\Configuration\Tag;
use App\Models\Customers\CustomerGroup;
use App\Models\Products\Attribute;
use App\Models\Products\AttributeGroup;
use App\Models\Products\Brand;
use App\Models\Products\Product;
use App\Models\Products\ProductOption;
use App\Models\Products\ProductType;
use App\Models\Products\ProductVariant;
use App\Models\Products\Price;
use App\Models\Products\MinableCoin;
use App\Models\Products\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['productType', 'brand', 'variants', 'collections', 'images']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%');
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
        $minableCoins = MinableCoin::active()->ordered()->get();

        return view('admin.pages.products.create', compact(
            'productTypes',
            'brands',
            'collections',
            'tags',
            'customerGroups',
            'productOptions',
            'attributes',
            'minableCoins'
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

            // Minable Coins
            'minable_coins' => 'nullable|array',
            'minable_coins.*' => 'exists:minable_coins,id',

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
                    ];
                }
                $product->customerGroups()->attach($customerGroupsData);
            }

            // Attach minable coins
            if ($request->filled('minable_coins')) {
                $minableCoinsData = [];
                foreach ($request->minable_coins as $index => $coinId) {
                    $minableCoinsData[$coinId] = ['position' => $index + 1];
                }
                $product->minableCoins()->sync($minableCoinsData);
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
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $product = Product::with([
            'productType',
            'brand',
            'variants.prices',
            'variants.values',
            'collections',
            'tags',
            'customerGroups',
            'productOptions.values',
            'minableCoins',
            'images',
            'attributes'
        ])->findOrFail($id);

        // Get all dependencies needed for the wizard
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
            ->orderBy('position')
            ->get();
        $attributeGroups = AttributeGroup::orderBy('position')->get();
        $minableCoins = MinableCoin::active()->ordered()->get();

        // Transform product data for the wizard
        $productData = $this->transformProductForWizard($product);

        return view('admin.pages.products.edit', compact(
            'product',
            'productData',
            'productTypes',
            'brands',
            'collections',
            'tags',
            'customerGroups',
            'productOptions',
            'attributes',
            'attributeGroups',
            'minableCoins'
        ));
    }

    /**
     * Transform product data for wizard format.
     */
    private function transformProductForWizard($product)
    {
        $productData = $product->toArray();

        // Direct columns are already in the array, no need to extract from JSON
        $productData['name'] = $product->name;
        $productData['slug'] = $product->slug;
        $productData['short_description'] = $product->short_description;
        $productData['description'] = $product->description;

        // SEO data from direct columns
        $productData['meta_title'] = $product->seo_title;
        $productData['meta_description'] = $product->seo_description;
        $productData['meta_keywords'] = $product->seo_keywords;

        // Load gallery images from ProductImage model
        $productData['gallery_images'] = $product->images()->ordered()->get()->map(function($img) {
            return [
                'id' => $img->id,
                'path' => $img->path,
                'url' => $img->url,
                'position' => $img->position,
                'is_primary' => $img->is_primary,
            ];
        })->toArray();

        // Transform variants with prices (prices are in cents, so we keep them as is for the wizard)
        $productData['variants'] = ($product->variants ?? collect())->map(function ($variant) {
            $defaultPrice = ($variant->prices ?? collect())->where('customer_group_id', null)->first();

            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'stock' => $variant->stock,
                'enabled' => $variant->purchasable === 'always',
                'price' => $defaultPrice ? (int)$defaultPrice->price : 0,
                'compare_price' => $defaultPrice && $defaultPrice->compare_price ? (int)$defaultPrice->compare_price : null,
                'min_quantity' => $defaultPrice ? (int)$defaultPrice->min_quantity : 1,
                'option_values' => ($variant->values ?? collect())->pluck('id')->toArray()
            ];
        })->toArray();

        // Transform product options
        $productData['productOptions'] = ($product->productOptions ?? collect())->map(function ($option) {
            $optionLabel = $option->label ?? $option->name ?? '';
            $optionLabel = is_array($optionLabel) ? ($optionLabel['en'] ?? $optionLabel[0] ?? '') : $optionLabel;

            return [
                'id' => $option->id,
                'option_id' => $option->id,
                'label' => $optionLabel,
                'values' => ($option->values ?? collect())->toArray(),
                'display_type' => $option->pivot->display_type ?? 'select',
                'required' => $option->pivot->required ?? false,
                'affects_price' => $option->pivot->affects_price ?? false,
                'affects_stock' => $option->pivot->affects_stock ?? false,
                'position' => $option->pivot->position ?? 0,
            ];
        })->toArray();

        // Transform minable coins (just the IDs for checkboxes)
        $productData['minableCoins'] = ($product->minableCoins ?? collect())->pluck('id')->toArray();

        return $productData;
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // Step 1: Basics
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'product_type_id' => 'required|exists:product_types,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:draft,published,archived,out_of_stock',
            'is_featured' => 'nullable|boolean',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',

            // Step 2: Options
            'product_options' => 'nullable|array',
            'product_options.*.option_id' => 'required|exists:product_options,id',
            'product_options.*.display_type' => 'required|string',
            'product_options.*.required' => 'required|boolean',
            'product_options.*.affects_price' => 'required|boolean',
            'product_options.*.affects_stock' => 'required|boolean',
            'product_options.*.position' => 'required|integer',

            // Step 3: Variants
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.sku' => 'required|string',
            'variants.*.option_values' => 'nullable|array',
            'variants.*.option_values.*' => 'exists:product_option_values,id',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.enabled' => 'required|boolean',

            // Step 4: Price
            'variants.*.price' => 'required|integer|min:0',
            'variants.*.compare_price' => 'nullable|integer|min:0',
            'variants.*.min_quantity' => 'required|integer|min:1',

            // Step 5: Images
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_thumbnail' => 'nullable|boolean',
            'deleted_media_ids' => 'nullable|array',
            'deleted_media_ids.*' => 'integer',

            // Attributes
            'attributes' => 'nullable|array',

            // Step 6: Finalization
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'minable_coins' => 'nullable|array',
            'minable_coins.*' => 'exists:minable_coins,id',
            'customer_groups' => 'nullable|array',
            'customer_groups.*' => 'exists:customer_groups,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            // Update product with direct columns
            $product->update([
                'product_type_id' => $request->product_type_id,
                'brand_id' => $request->brand_id,
                'status' => $request->status,
                'is_featured' => $request->is_featured ?? false,
                'slug' => $request->slug,
                'name' => $request->name,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'seo_title' => $request->meta_title,
                'seo_description' => $request->meta_description,
                'seo_keywords' => $request->meta_keywords,
            ]);

            // Sync collections
            if ($request->has('collections')) {
                $collectionsData = [];
                foreach ($request->collections as $index => $collectionId) {
                    $collectionsData[$collectionId] = ['position' => $index + 1];
                }
                $product->collections()->sync($collectionsData);
            }

            // Sync tags
            if ($request->has('tags')) {
                DB::table('taggables')
                    ->where('taggable_type', 'product')
                    ->where('taggable_id', $product->id)
                    ->delete();

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

            // Sync customer groups
            if ($request->has('customer_groups')) {
                $customerGroupsData = [];
                foreach ($request->customer_groups as $groupId) {
                    $customerGroupsData[$groupId] = [
                        'purchasable' => true,
                        'visible' => true,
                    ];
                }
                $product->customerGroups()->sync($customerGroupsData);
            }

            // Sync minable coins
            if ($request->has('minable_coins')) {
                $minableCoinsData = [];
                foreach ($request->minable_coins as $index => $coinId) {
                    $minableCoinsData[$coinId] = ['position' => $index + 1];
                }
                $product->minableCoins()->sync($minableCoinsData);
            } else {
                // If no coins provided, detach all
                $product->minableCoins()->sync([]);
            }

            // Sync product attributes with values
            // IMPORTANT: Use input('attributes') not $request->attributes (which is a reserved Symfony property)
            $requestAttributes = $request->input('attributes', []);
            \Log::info('DEBUG: Request attributes data:', ['data' => $requestAttributes]);

            if (!empty($requestAttributes)) {
                $attributesData = [];
                foreach ($requestAttributes as $handle => $value) {
                    // Find attribute by handle
                    $attribute = \App\Models\Products\Attribute::where('handle', $handle)
                        ->where('attribute_type', 'product')
                        ->first();

                    \Log::info('DEBUG: Processing attribute', [
                        'handle' => $handle,
                        'value' => $value,
                        'found' => $attribute ? 'yes' : 'no',
                        'attribute_id' => $attribute?->id
                    ]);

                    if ($attribute && $value !== null && $value !== '') {
                        $attributesData[$attribute->id] = ['value' => $value];
                    }
                }

                \Log::info('DEBUG: Syncing attributes:', ['data' => $attributesData]);
                $product->attributes()->sync($attributesData);
            } else {
                // If no attributes provided, detach all
                \Log::info('DEBUG: No attributes in request, detaching all');
                $product->attributes()->sync([]);
            }

            // Sync product options
            if ($request->has('product_options')) {
                $product->productOptions()->detach();
                foreach ($request->product_options as $optionData) {
                    $product->productOptions()->attach($optionData['option_id'], [
                        'position' => $optionData['position'],
                        'display_type' => $optionData['display_type'],
                        'required' => $optionData['required'],
                        'affects_price' => $optionData['affects_price'],
                        'affects_stock' => $optionData['affects_stock'],
                    ]);
                }
            }

            // Update or create variants
            $existingVariantIds = [];
            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {
                    if (isset($variantData['id'])) {
                        // Update existing variant
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant) {
                            $variant->update([
                                'sku' => $variantData['sku'],
                                'stock' => $variantData['stock'],
                                'purchasable' => $variantData['enabled'] ? 'always' : 'never',
                            ]);

                            // Sync option values
                            if (isset($variantData['option_values'])) {
                                $variant->values()->sync($variantData['option_values']);
                            }

                            // Update price
                            $price = $variant->prices()->where('customer_group_id', null)->first();
                            if ($price) {
                                $price->update([
                                    'price' => $variantData['price'],
                                    'compare_price' => $variantData['compare_price'] ?? null,
                                    'min_quantity' => $variantData['min_quantity'] ?? 1,
                                ]);
                            } else {
                                Price::create([
                                    'customer_group_id' => null,
                                    'priceable_type' => 'product_variant',
                                    'priceable_id' => $variant->id,
                                    'price' => $variantData['price'],
                                    'compare_price' => $variantData['compare_price'] ?? null,
                                    'min_quantity' => $variantData['min_quantity'] ?? 1,
                                ]);
                            }

                            $existingVariantIds[] = $variant->id;
                        }
                    } else {
                        // Create new variant
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $variantData['sku'],
                            'stock' => $variantData['stock'],
                            'purchasable' => $variantData['enabled'] ? 'always' : 'never',
                            'min_quantity' => 1,
                            'quantity_increment' => 1,
                            'backorder' => 0,
                            'shippable' => true,
                            'attribute_data' => [],
                        ]);

                        // Attach option values
                        if (isset($variantData['option_values'])) {
                            $variant->values()->attach($variantData['option_values']);
                        }

                        // Create price
                        Price::create([
                            'customer_group_id' => null,
                            'priceable_type' => 'product_variant',
                            'priceable_id' => $variant->id,
                            'price' => $variantData['price'],
                            'compare_price' => $variantData['compare_price'] ?? null,
                            'min_quantity' => $variantData['min_quantity'] ?? 1,
                        ]);

                        $existingVariantIds[] = $variant->id;
                    }
                }
            }

            // Delete variants that are no longer present
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }

                // Store new thumbnail
                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
                $product->update(['thumbnail' => $thumbnailPath]);
            } elseif ($request->remove_thumbnail) {
                // Remove thumbnail
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                    $product->update(['thumbnail' => null]);
                }
            }

            // Handle gallery images (ProductImage model)
            if ($request->hasFile('gallery')) {
                $maxPosition = $product->images()->max('position') ?? 0;

                foreach ($request->file('gallery') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'position' => $maxPosition + $index + 1,
                        'alt_text' => $product->name,
                        'is_primary' => false,
                    ]);
                }
            }

            // Delete removed gallery images
            if ($request->filled('deleted_media_ids')) {
                foreach ($request->deleted_media_ids as $imageId) {
                    $productImage = ProductImage::find($imageId);
                    if ($productImage && $productImage->product_id == $product->id) {
                        // Delete file from storage
                        Storage::disk('public')->delete($productImage->path);
                        // Delete record
                        $productImage->delete();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'redirect' => route('admin.products.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
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

    /**
     * Show the wizard form for creating a new product.
     */
    public function wizard()
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
            ->orderBy('position')
            ->get();
        $attributeGroups = AttributeGroup::orderBy('position')->get();

        return view('admin.pages.products.wizard', compact(
            'productTypes',
            'brands',
            'collections',
            'tags',
            'customerGroups',
            'productOptions',
            'attributes',
            'attributeGroups'
        ));
    }

    /**
     * Store a newly created product from wizard.
     */
    public function storeWizard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Step 1: Basics
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'product_type_id' => 'required|exists:product_types,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:draft,published,archived,out_of_stock',
            'is_featured' => 'nullable|boolean',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'attributes' => 'nullable|array',

            // Step 2: Options
            'product_options' => 'nullable|array',
            'product_options.*.option_id' => 'required|exists:product_options,id',
            'product_options.*.display_type' => 'required|string',
            'product_options.*.required' => 'required|boolean',
            'product_options.*.affects_price' => 'required|boolean',
            'product_options.*.affects_stock' => 'required|boolean',
            'product_options.*.position' => 'required|integer',

            // Step 3: Variants
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|unique:product_variants,sku',
            'variants.*.option_values' => 'nullable|array',
            'variants.*.option_values.*' => 'exists:product_option_values,id',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.enabled' => 'required|boolean',

            // Step 4: Price (simplified without customer groups)
            'variants.*.price' => 'required|integer|min:0',
            'variants.*.compare_price' => 'nullable|integer|min:0',
            'variants.*.min_quantity' => 'required|integer|min:1',

            // Step 5: Images
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            // Attributes
            'attributes' => 'nullable|array',

            // Step 6: Finalization
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'minable_coins' => 'nullable|array',
            'minable_coins.*' => 'exists:minable_coins,id',
            'customer_groups' => 'nullable|array',
            'customer_groups.*' => 'exists:customer_groups,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Store thumbnail if uploaded
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            // Create product with direct columns
            $product = Product::create([
                'product_type_id' => $request->product_type_id,
                'brand_id' => $request->brand_id,
                'status' => $request->status,
                'is_featured' => $request->is_featured ?? false,
                'slug' => $request->slug,
                'name' => $request->name,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'seo_title' => $request->meta_title,
                'seo_description' => $request->meta_description,
                'seo_keywords' => $request->meta_keywords,
                'thumbnail' => $thumbnailPath,
                'attribute_data' => ['custom_attributes' => []], // Only custom_attributes in JSON now
            ]);

            // Attach collections
            if ($request->filled('collections')) {
                $collectionsData = [];
                foreach ($request->collections as $index => $collectionId) {
                    $collectionsData[$collectionId] = ['position' => $index + 1];
                }
                $product->collections()->attach($collectionsData);
            }

            // Attach tags
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
                    ];
                }
                $product->customerGroups()->attach($customerGroupsData);
            }

            // Attach minable coins
            if ($request->filled('minable_coins')) {
                $minableCoinsData = [];
                foreach ($request->minable_coins as $index => $coinId) {
                    $minableCoinsData[$coinId] = ['position' => $index + 1];
                }
                $product->minableCoins()->sync($minableCoinsData);
            }

            // Attach product attributes with values
            // IMPORTANT: Use input('attributes') not $request->attributes (which is a reserved Symfony property)
            $requestAttributes = $request->input('attributes', []);
            if (!empty($requestAttributes)) {
                $attributesData = [];
                foreach ($requestAttributes as $handle => $value) {
                    // Find attribute by handle
                    $attribute = \App\Models\Products\Attribute::where('handle', $handle)
                        ->where('attribute_type', 'product')
                        ->first();

                    if ($attribute && $value !== null && $value !== '') {
                        $attributesData[$attribute->id] = ['value' => $value];
                    }
                }

                if (!empty($attributesData)) {
                    $product->attributes()->attach($attributesData);
                }
            }

            // Attach product options with configuration
            if ($request->filled('product_options')) {
                foreach ($request->product_options as $optionData) {
                    $product->productOptions()->attach($optionData['option_id'], [
                        'position' => $optionData['position'],
                        'display_type' => $optionData['display_type'],
                        'required' => $optionData['required'],
                        'affects_price' => $optionData['affects_price'],
                        'affects_stock' => $optionData['affects_stock'],
                    ]);
                }
            }

            // Create variants with prices
            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'stock' => $variantData['stock'],
                        'purchasable' => $variantData['enabled'] ? 'always' : 'never',
                        'min_quantity' => 1,
                        'quantity_increment' => 1,
                        'backorder' => 0,
                        'shippable' => true,
                        'attribute_data' => [],
                    ]);

                    // Attach option values to variant
                    if (isset($variantData['option_values']) && is_array($variantData['option_values'])) {
                        $variant->values()->attach($variantData['option_values']);
                    }

                    // Create default price for variant (no customer group)
                    Price::create([
                        'customer_group_id' => null,
                        'priceable_type' => 'product_variant',
                        'priceable_id' => $variant->id,
                        'price' => $variantData['price'],
                        'compare_price' => $variantData['compare_price'] ?? null,
                        'min_quantity' => $variantData['min_quantity'] ?? 1,
                    ]);
                }
            }

            // Handle gallery images (create ProductImage records)
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'position' => $index + 1,
                        'alt_text' => $product->name,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'redirect' => route('admin.products.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }
}
