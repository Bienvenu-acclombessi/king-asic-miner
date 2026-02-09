<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration\ProductSlide;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductSlideController extends Controller
{
    /**
     * Display a listing of product slides.
     */
    public function index(Request $request)
    {
        $query = ProductSlide::with('product');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(attribute_data, '$.name')) LIKE ?", ['%' . $search . '%']);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $productSlides = $query->orderBy('position')->paginate(15);

        return view('admin.pages.product-slides.index', compact('productSlides'));
    }

    /**
     * Show the form for creating a new product slide.
     */
    public function create()
    {
        return response()->json([
            'success' => true,
            'products' => Product::where('status', 'published')
                ->get()
                ->map(function($product) {
                    $name = $product->attribute_data['name'] ?? 'N/A';
                    return [
                        'id' => $product->id,
                        'name' => is_array($name) ? ($name['en'] ?? $name[0] ?? 'N/A') : $name,
                    ];
                })
        ]);
    }

    /**
     * Store a newly created product slide.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'background_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get the last position
            $lastPosition = ProductSlide::max('position') ?? 0;

            // Store the background image
            $backgroundPath = null;
            if ($request->hasFile('background_image')) {
                $backgroundPath = $request->file('background_image')->store('slides/backgrounds', 'public');
            }

            ProductSlide::create([
                'product_id' => $request->product_id,
                'background_image' => $backgroundPath,
                'is_active' => $request->is_active,
                'position' => $lastPosition + 1,
            ]);

            return redirect()->route('admin.product-slides.index')
                ->with('success', 'Product slide created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating product slide: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified product slide.
     */
    public function edit($id)
    {
        $productSlide = ProductSlide::with('product')->findOrFail($id);
        $products = Product::where('status', 'published')
            ->get()
            ->map(function($product) {
                $name = $product->attribute_data['name'] ?? 'N/A';
                return [
                    'id' => $product->id,
                    'name' => is_array($name) ? ($name['en'] ?? $name[0] ?? 'N/A') : $name,
                ];
            });

        return response()->json([
            'success' => true,
            'productSlide' => $productSlide,
            'products' => $products
        ]);
    }

    /**
     * Update the specified product slide.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'required|boolean',
            'remove_background' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $productSlide = ProductSlide::findOrFail($id);

            $data = [
                'product_id' => $request->product_id,
                'is_active' => $request->is_active,
            ];

            // Handle background image upload
            if ($request->hasFile('background_image')) {
                // Delete old image
                if ($productSlide->background_image) {
                    Storage::disk('public')->delete($productSlide->background_image);
                }
                $data['background_image'] = $request->file('background_image')->store('slides/backgrounds', 'public');
            } elseif ($request->remove_background) {
                // Remove background image
                if ($productSlide->background_image) {
                    Storage::disk('public')->delete($productSlide->background_image);
                }
                $data['background_image'] = null;
            }

            $productSlide->update($data);

            return redirect()->route('admin.product-slides.index')
                ->with('success', 'Product slide updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating product slide: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product slide.
     */
    public function destroy($id)
    {
        try {
            $productSlide = ProductSlide::findOrFail($id);

            // Delete background image
            if ($productSlide->background_image) {
                Storage::disk('public')->delete($productSlide->background_image);
            }

            $productSlide->delete();

            return redirect()->route('admin.product-slides.index')
                ->with('success', 'Product slide deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting product slide: ' . $e->getMessage());
        }
    }

    /**
     * Update the positions of product slides.
     */
    public function updatePositions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:product_slides,id',
            'positions.*.position' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->positions as $positionData) {
                ProductSlide::where('id', $positionData['id'])
                    ->update(['position' => $positionData['position']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Positions updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating positions: ' . $e->getMessage()
            ], 500);
        }
    }
}
