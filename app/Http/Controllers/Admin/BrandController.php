<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of brands.
     */
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $brands = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pages.brands.index', compact('brands'));
    }

    /**
     * Store a newly created brand.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Prepare attribute data
            $attributeData = [
                'slug' => Str::slug($request->name),
            ];

            if ($request->filled('description')) {
                $attributeData['description'] = $request->description;
            }

            if ($request->filled('website')) {
                $attributeData['website'] = $request->website;
            }

            // Create brand
            Brand::create([
                'name' => $request->name,
                'attribute_data' => $attributeData,
            ]);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating brand: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified brand (AJAX).
     */
    public function edit($id)
    {
        try {
            $brand = Brand::findOrFail($id);

            return response()->json([
                'success' => true,
                'brand' => $brand
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }
    }

    /**
     * Update the specified brand.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $brand = Brand::findOrFail($id);

            // Prepare attribute data
            $attributeData = [
                'slug' => Str::slug($request->name),
            ];

            if ($request->filled('description')) {
                $attributeData['description'] = $request->description;
            }

            if ($request->filled('website')) {
                $attributeData['website'] = $request->website;
            }

            // Merge with existing attribute data to preserve other fields
            $existingData = $brand->attribute_data ?? [];
            $attributeData = array_merge($existingData, $attributeData);

            // Update brand
            $brand->update([
                'name' => $request->name,
                'attribute_data' => $attributeData,
            ]);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating brand: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified brand.
     */
    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);

            // Check if brand has products
            if ($brand->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete brand with associated products. Please remove products first.');
            }

            $brand->delete();

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting brand: ' . $e->getMessage());
        }
    }
}
