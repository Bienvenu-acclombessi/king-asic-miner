<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration\CollectionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CollectionGroupController extends Controller
{
    /**
     * Display a listing of collection groups.
     */
    public function index(Request $request)
    {
        $query = CollectionGroup::with('collections');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('handle', 'LIKE', '%' . $search . '%');
            });
        }

        $collectionGroups = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pages.collection-groups.index', compact('collectionGroups'));
    }

    /**
     * Store a newly created collection group.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'handle' => 'required|string|max:255|unique:collection_groups,handle',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            CollectionGroup::create([
                'name' => $request->name,
                'handle' => Str::slug($request->handle),
            ]);

            return redirect()->route('admin.collection-groups.index')
                ->with('success', 'Collection group created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating collection group: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified collection group (AJAX).
     */
    public function edit($id)
    {
        try {
            $group = CollectionGroup::findOrFail($id);

            return response()->json([
                'success' => true,
                'group' => $group
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Collection group not found'
            ], 404);
        }
    }

    /**
     * Update the specified collection group.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'handle' => 'required|string|max:255|unique:collection_groups,handle,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $group = CollectionGroup::findOrFail($id);

            $group->update([
                'name' => $request->name,
                'handle' => Str::slug($request->handle),
            ]);

            return redirect()->route('admin.collection-groups.index')
                ->with('success', 'Collection group updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating collection group: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified collection group.
     */
    public function destroy($id)
    {
        try {
            $group = CollectionGroup::findOrFail($id);

            // Check if group has collections
            if ($group->collections()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete collection group with associated categories. Please delete or reassign categories first.');
            }

            $group->delete();

            return redirect()->route('admin.collection-groups.index')
                ->with('success', 'Collection group deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting collection group: ' . $e->getMessage());
        }
    }
}
