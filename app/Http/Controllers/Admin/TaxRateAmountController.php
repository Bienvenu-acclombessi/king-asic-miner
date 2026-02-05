<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Taxes\TaxRateAmount;
use App\Models\Taxes\TaxClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxRateAmountController extends Controller
{
    /**
     * Store a newly created tax rate amount.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_class_id' => 'required|exists:tax_classes,id',
            'tax_rate_id' => 'required|exists:tax_rates,id',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Check if this combination already exists
            $exists = TaxRateAmount::where('tax_class_id', $request->tax_class_id)
                ->where('tax_rate_id', $request->tax_rate_id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'This tax rate is already assigned to this tax class.')
                    ->withInput();
            }

            // Create tax rate amount
            TaxRateAmount::create([
                'tax_class_id' => $request->tax_class_id,
                'tax_rate_id' => $request->tax_rate_id,
                'percentage' => $request->percentage,
            ]);

            return redirect()->back()
                ->with('success', 'Tax rate added successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error adding tax rate: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified tax rate amount (AJAX).
     */
    public function edit($id)
    {
        try {
            $rateAmount = TaxRateAmount::with(['taxRate'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'rateAmount' => $rateAmount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tax rate amount not found'
            ], 404);
        }
    }

    /**
     * Update the specified tax rate amount.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $rateAmount = TaxRateAmount::findOrFail($id);

            // Update tax rate amount
            $rateAmount->update([
                'percentage' => $request->percentage,
            ]);

            return redirect()->back()
                ->with('success', 'Tax rate percentage updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating tax rate percentage: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified tax rate amount.
     */
    public function destroy($id)
    {
        try {
            $rateAmount = TaxRateAmount::findOrFail($id);
            $rateAmount->delete();

            return redirect()->back()
                ->with('success', 'Tax rate removed successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error removing tax rate: ' . $e->getMessage());
        }
    }
}
