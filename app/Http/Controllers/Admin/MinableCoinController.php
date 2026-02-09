<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products\MinableCoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MinableCoinController extends Controller
{
    /**
     * Display a listing of minable coins.
     */
    public function index(Request $request)
    {
        $query = MinableCoin::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('symbol', 'LIKE', '%' . $search . '%')
                  ->orWhere('algorithm', 'LIKE', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $minableCoins = $query->ordered()->paginate(15);

        return view('admin.pages.minable-coins.index', compact('minableCoins'));
    }

    /**
     * Show the form for creating a new minable coin.
     */
    public function create()
    {
        return response()->json(['success' => true]);
    }

    /**
     * Store a newly created minable coin.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10|unique:minable_coins,symbol',
            'algorithm' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'difficulty' => 'nullable|string',
            'block_time' => 'nullable|integer|min:1',
            'block_reward' => 'nullable|numeric|min:0',
            'default_price' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:7',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get the last position
            $lastPosition = MinableCoin::max('position') ?? 0;

            // Store the logo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('coins/logos', 'public');
            }

            MinableCoin::create([
                'name' => $request->name,
                'symbol' => strtoupper($request->symbol),
                'algorithm' => $request->algorithm,
                'logo' => $logoPath,
                'difficulty' => $request->difficulty,
                'block_time' => $request->block_time,
                'block_reward' => $request->block_reward,
                'default_price' => $request->default_price ?? 0,
                'color' => $request->color,
                'is_active' => $request->is_active,
                'position' => $lastPosition + 1,
            ]);

            return redirect()->route('admin.minable-coins.index')
                ->with('success', 'Minable coin created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating minable coin: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified minable coin.
     */
    public function edit($id)
    {
        $minableCoin = MinableCoin::findOrFail($id);

        return response()->json([
            'success' => true,
            'minableCoin' => $minableCoin
        ]);
    }

    /**
     * Update the specified minable coin.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10|unique:minable_coins,symbol,' . $id,
            'algorithm' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'difficulty' => 'nullable|string',
            'block_time' => 'nullable|integer|min:1',
            'block_reward' => 'nullable|numeric|min:0',
            'default_price' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:7',
            'is_active' => 'required|boolean',
            'remove_logo' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $minableCoin = MinableCoin::findOrFail($id);

            $data = [
                'name' => $request->name,
                'symbol' => strtoupper($request->symbol),
                'algorithm' => $request->algorithm,
                'difficulty' => $request->difficulty,
                'block_time' => $request->block_time,
                'block_reward' => $request->block_reward,
                'default_price' => $request->default_price ?? 0,
                'color' => $request->color,
                'is_active' => $request->is_active,
            ];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($minableCoin->logo) {
                    Storage::disk('public')->delete($minableCoin->logo);
                }
                $data['logo'] = $request->file('logo')->store('coins/logos', 'public');
            } elseif ($request->remove_logo) {
                // Remove logo
                if ($minableCoin->logo) {
                    Storage::disk('public')->delete($minableCoin->logo);
                }
                $data['logo'] = null;
            }

            $minableCoin->update($data);

            return redirect()->route('admin.minable-coins.index')
                ->with('success', 'Minable coin updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating minable coin: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified minable coin.
     */
    public function destroy($id)
    {
        try {
            $minableCoin = MinableCoin::findOrFail($id);

            // Check if coin is being used by products
            if ($minableCoin->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete this coin because it is associated with ' . $minableCoin->products()->count() . ' product(s).');
            }

            // Delete logo
            if ($minableCoin->logo) {
                Storage::disk('public')->delete($minableCoin->logo);
            }

            $minableCoin->delete();

            return redirect()->route('admin.minable-coins.index')
                ->with('success', 'Minable coin deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting minable coin: ' . $e->getMessage());
        }
    }

    /**
     * Update the positions of minable coins.
     */
    public function updatePositions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'positions' => 'required|array',
            'positions.*.id' => 'required|exists:minable_coins,id',
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
                MinableCoin::where('id', $positionData['id'])
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
