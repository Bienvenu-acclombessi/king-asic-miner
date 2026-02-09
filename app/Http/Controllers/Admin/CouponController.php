<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Collection;
use App\Models\Discounts\Discount;
use App\Models\Products\Brand;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Discount::whereNotNull('coupon');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('coupon', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)
                          ->where(function ($q) {
                              $q->whereNull('ends_at')
                                ->orWhere('ends_at', '>', now());
                          });
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'expired':
                    $query->where('ends_at', '<', now());
                    break;
            }
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.pages.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'coupon' => 'required|string|max:50|unique:discounts,coupon',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'discount_value' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'min_qty' => 'nullable|integer|min:1',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'allowed_emails' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Handle checkbox fields (they're not sent if unchecked)
        $validated['coupon'] = strtoupper($validated['coupon']);
        $validated['handle'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['apply_to_shipping'] = $request->has('apply_to_shipping') ? 1 : 0;
        $validated['exclude_sale_items'] = $request->has('exclude_sale_items') ? 1 : 0;
        $validated['individual_use'] = $request->has('individual_use') ? 1 : 0;
        $validated['free_shipping'] = $request->has('free_shipping') ? 1 : 0;

        // Ensure empty dates are null
        $validated['starts_at'] = $validated['starts_at'] ?: null;
        $validated['ends_at'] = $validated['ends_at'] ?: null;

        Discount::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Coupon created successfully!'
        ]);
    }

    public function edit(Discount $coupon)
    {
        return response()->json($coupon);
    }

    public function update(Request $request, Discount $coupon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'coupon' => 'required|string|max:50|unique:discounts,coupon,' . $coupon->id,
            'type' => 'required|in:percentage,fixed,free_shipping',
            'discount_value' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'min_qty' => 'nullable|integer|min:1',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'allowed_emails' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Handle checkbox fields (they're not sent if unchecked)
        $validated['coupon'] = strtoupper($validated['coupon']);
        $validated['handle'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['apply_to_shipping'] = $request->has('apply_to_shipping') ? 1 : 0;
        $validated['exclude_sale_items'] = $request->has('exclude_sale_items') ? 1 : 0;
        $validated['individual_use'] = $request->has('individual_use') ? 1 : 0;
        $validated['free_shipping'] = $request->has('free_shipping') ? 1 : 0;

        // Ensure empty dates are null
        $validated['starts_at'] = $validated['starts_at'] ?: null;
        $validated['ends_at'] = $validated['ends_at'] ?: null;

        $coupon->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Coupon updated successfully!'
        ]);
    }

    public function destroy(Discount $coupon)
    {
        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully!'
        ]);
    }

    /**
     * Get products for select dropdown
     */
    public function getProducts(Request $request)
    {
        $search = $request->get('search', '');

        $products = Product::select('id', 'name')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->limit(50)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'text' => $product->name,
                ];
            });

        return response()->json($products);
    }

    /**
     * Get collections for select dropdown
     */
    public function getCollections(Request $request)
    {
        $search = $request->get('search', '');

        $collections = Collection::select('id', 'name')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->limit(50)
            ->get()
            ->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'text' => $collection->name,
                ];
            });

        return response()->json($collections);
    }

    /**
     * Get brands for select dropdown
     */
    public function getBrands(Request $request)
    {
        $search = $request->get('search', '');

        $brands = Brand::select('id', 'name')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->limit(50)
            ->get()
            ->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'text' => $brand->name,
                ];
            });

        return response()->json($brands);
    }
}
