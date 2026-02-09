<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShippingMethodController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingMethod::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $shippingMethods = $query->orderBy('display_order')->orderBy('name')->paginate(20);

        return view('admin.pages.shipping-methods.index', compact('shippingMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_type' => 'required|in:fixed,percentage,free',
            'price' => 'required_unless:price_type,free|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'estimated_days_min' => 'nullable|integer|min:0',
            'estimated_days_max' => 'nullable|integer|min:0',
            'display_order' => 'nullable|integer',
        ]);

        // Generate handle
        $validated['handle'] = Str::slug($validated['name']);

        // Handle checkbox fields
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Set price to 0 if free shipping
        if ($validated['price_type'] === 'free') {
            $validated['price'] = 0;
        }

        // Ensure display_order has a value
        if (!isset($validated['display_order'])) {
            $validated['display_order'] = ShippingMethod::max('display_order') + 1;
        }

        ShippingMethod::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Shipping method created successfully!'
        ]);
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        return response()->json($shippingMethod);
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_type' => 'required|in:fixed,percentage,free',
            'price' => 'required_unless:price_type,free|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'estimated_days_min' => 'nullable|integer|min:0',
            'estimated_days_max' => 'nullable|integer|min:0',
            'display_order' => 'nullable|integer',
        ]);

        // Update handle if name changed
        if ($validated['name'] !== $shippingMethod->name) {
            $validated['handle'] = Str::slug($validated['name']);
        }

        // Handle checkbox fields
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Set price to 0 if free shipping
        if ($validated['price_type'] === 'free') {
            $validated['price'] = 0;
        }

        $shippingMethod->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Shipping method updated successfully!'
        ]);
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();

        return response()->json([
            'success' => true,
            'message' => 'Shipping method deleted successfully!'
        ]);
    }
}
