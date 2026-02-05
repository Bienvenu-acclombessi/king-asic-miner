<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of product types.
     */
    public function index(Request $request)
    {
        $query = ProductType::withCount('products');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$'))) LIKE ?", ['%' . strtolower($search) . '%']);
            });
        }

        $productTypes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pages.product-types.index', compact('productTypes'));
    }

    /**
     * Store a newly created product type.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Prepare name data (JSON format)
            $nameData = [
                'en' => $request->name,
            ];

            // Create product type
            ProductType::create([
                'name' => $nameData,
            ]);

            return redirect()->route('admin.product-types.index')
                ->with('success', 'Product Type created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating product type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified product type (AJAX).
     */
    public function edit($id)
    {
        try {
            $productType = ProductType::findOrFail($id);

            return response()->json([
                'success' => true,
                'productType' => $productType
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Type not found'
            ], 404);
        }
    }

    /**
     * Update the specified product type.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $productType = ProductType::findOrFail($id);

            // Prepare name data (JSON format)
            $nameData = [
                'en' => $request->name,
            ];

            // Update product type
            $productType->update([
                'name' => $nameData,
            ]);

            return redirect()->route('admin.product-types.index')
                ->with('success', 'Product Type updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating product type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product type.
     */
    public function destroy($id)
    {
        try {
            $productType = ProductType::findOrFail($id);

            // Check if product type has products
            if ($productType->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete product type with associated products. Please remove or reassign products first.');
            }

            $productType->delete();

            return redirect()->route('admin.product-types.index')
                ->with('success', 'Product Type deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting product type: ' . $e->getMessage());
        }
    }
}
