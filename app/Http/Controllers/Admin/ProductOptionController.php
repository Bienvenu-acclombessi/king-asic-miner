<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductOptionController extends Controller
{
    /**
     * Display a listing of product options.
     */
    public function index(Request $request)
    {
        $query = ProductOption::withCount(['products', 'values']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$'))) LIKE ?", ['%' . strtolower($search) . '%'])
                  ->orWhere('handle', 'like', '%' . $search . '%');
            });
        }

        // Shared filter
        if ($request->filled('shared')) {
            $query->where('shared', $request->shared === 'true');
        }

        $productOptions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pages.product-options.index', compact('productOptions'));
    }

    /**
     * Store a newly created product option.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'handle' => 'nullable|string|max:255|unique:product_options,handle',
            'shared' => 'nullable|boolean',
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

            // Prepare label data (JSON format)
            $labelData = null;
            if ($request->filled('label')) {
                $labelData = [
                    'en' => $request->label,
                ];
            }

            // Generate handle if not provided
            $handle = $request->filled('handle') ? $request->handle : Str::slug($request->name);

            // Create product option
            ProductOption::create([
                'name' => $nameData,
                'label' => $labelData,
                'handle' => $handle,
                'shared' => $request->boolean('shared', false),
            ]);

            return redirect()->route('admin.product-options.index')
                ->with('success', 'Product Option created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating product option: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified product option (AJAX).
     */
    public function edit($id)
    {
        try {
            $productOption = ProductOption::findOrFail($id);

            return response()->json([
                'success' => true,
                'productOption' => $productOption
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product Option not found'
            ], 404);
        }
    }

    /**
     * Update the specified product option.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'handle' => 'nullable|string|max:255|unique:product_options,handle,' . $id,
            'shared' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $productOption = ProductOption::findOrFail($id);

            // Prepare name data (JSON format)
            $nameData = [
                'en' => $request->name,
            ];

            // Prepare label data (JSON format)
            $labelData = null;
            if ($request->filled('label')) {
                $labelData = [
                    'en' => $request->label,
                ];
            }

            // Generate handle if not provided
            $handle = $request->filled('handle') ? $request->handle : Str::slug($request->name);

            // Update product option
            $productOption->update([
                'name' => $nameData,
                'label' => $labelData,
                'handle' => $handle,
                'shared' => $request->boolean('shared', false),
            ]);

            return redirect()->route('admin.product-options.index')
                ->with('success', 'Product Option updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating product option: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product option.
     */
    public function destroy($id)
    {
        try {
            $productOption = ProductOption::findOrFail($id);

            // Check if product option has products
            if ($productOption->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete product option with associated products. Please remove products first.');
            }

            // Check if product option has values
            if ($productOption->values()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete product option with option values. Please delete values first.');
            }

            $productOption->delete();

            return redirect()->route('admin.product-options.index')
                ->with('success', 'Product Option deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting product option: ' . $e->getMessage());
        }
    }
}
