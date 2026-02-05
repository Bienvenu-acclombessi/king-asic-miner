<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Taxes\TaxClass;
use App\Models\Taxes\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxClassController extends Controller
{
    /**
     * Display a listing of tax classes.
     */
    public function index(Request $request)
    {
        $query = TaxClass::withCount('taxRateAmounts');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        $taxClasses = $query->orderBy('default', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pages.tax-classes.index', compact('taxClasses'));
    }

    /**
     * Display the specified tax class with its tax rates.
     */
    public function show($id)
    {
        $taxClass = TaxClass::with(['taxRateAmounts.taxRate.taxZone'])->findOrFail($id);

        // Get tax rates that are not yet assigned to this class
        $assignedTaxRateIds = $taxClass->taxRateAmounts->pluck('tax_rate_id')->toArray();
        $availableTaxRates = TaxRate::with('taxZone')
            ->whereNotIn('id', $assignedTaxRateIds)
            ->orderBy('name')
            ->get();

        return view('admin.pages.tax-classes.show', compact('taxClass', 'availableTaxRates'));
    }

    /**
     * Store a newly created tax class.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tax_classes,name',
            'default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // If setting as default, unset other defaults
            if ($request->has('default') && $request->default) {
                TaxClass::where('default', true)->update(['default' => false]);
            }

            // Create tax class
            TaxClass::create([
                'name' => $request->name,
                'default' => $request->has('default') ? true : false,
            ]);

            return redirect()->route('admin.tax-classes.index')
                ->with('success', 'Tax class created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating tax class: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified tax class (AJAX).
     */
    public function edit($id)
    {
        try {
            $taxClass = TaxClass::findOrFail($id);

            return response()->json([
                'success' => true,
                'taxClass' => $taxClass
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tax class not found'
            ], 404);
        }
    }

    /**
     * Update the specified tax class.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tax_classes,name,' . $id,
            'default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $taxClass = TaxClass::findOrFail($id);

            // If setting as default, unset other defaults
            if ($request->has('default') && $request->default) {
                TaxClass::where('default', true)->where('id', '!=', $id)->update(['default' => false]);
            }

            // Update tax class
            $taxClass->update([
                'name' => $request->name,
                'default' => $request->has('default') ? true : false,
            ]);

            return redirect()->route('admin.tax-classes.index')
                ->with('success', 'Tax class updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating tax class: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified tax class.
     */
    public function destroy($id)
    {
        try {
            $taxClass = TaxClass::findOrFail($id);

            // Check if tax class has tax rate amounts
            if ($taxClass->taxRateAmounts()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete tax class with associated tax rates. Please remove tax rates first.');
            }

            // Prevent deleting default tax class
            if ($taxClass->default) {
                return redirect()->back()
                    ->with('error', 'Cannot delete the default tax class. Please set another tax class as default first.');
            }

            $taxClass->delete();

            return redirect()->route('admin.tax-classes.index')
                ->with('success', 'Tax class deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting tax class: ' . $e->getMessage());
        }
    }
}
