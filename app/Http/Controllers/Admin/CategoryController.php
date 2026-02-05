<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Collection;
use App\Models\Configuration\CollectionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Collection::with(['group', 'products']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(attribute_data, '$.name'))) LIKE ?", ['%' . strtolower($search) . '%']);
            });
        }

        // Collection group filter
        if ($request->filled('collection_group')) {
            $query->where('collection_group_id', $request->collection_group);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(15);
        $collectionGroups = CollectionGroup::all();
        $parentCategories = Collection::whereNull('parent_id')->get();

        return view('admin.pages.categories.index', compact('categories', 'collectionGroups', 'parentCategories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'collection_group_id' => 'required|exists:collection_groups,id',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:collections,id',
            'type' => 'required|in:static,dynamic',
            'sort' => 'required|in:custom,alphabetical,newest,oldest',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Prepare attribute data
            $attributeData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ];

            if ($request->filled('description')) {
                $attributeData['description'] = $request->description;
            }

            // Create category
            $category = Collection::create([
                'collection_group_id' => $request->collection_group_id,
                'parent_id' => $request->parent_id,
                'type' => $request->type,
                'sort' => $request->sort,
                'attribute_data' => $attributeData,
            ]);

            // Handle nested set structure (_lft, _rgt)
            $this->updateNestedSet($category);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified category (AJAX).
     */
    public function edit($id)
    {
        try {
            $category = Collection::findOrFail($id);

            return response()->json([
                'success' => true,
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'collection_group_id' => 'required|exists:collection_groups,id',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:collections,id',
            'type' => 'required|in:static,dynamic',
            'sort' => 'required|in:custom,alphabetical,newest,oldest',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category = Collection::findOrFail($id);

            // Check if trying to set itself as parent
            if ($request->parent_id == $id) {
                return redirect()->back()
                    ->with('error', 'A category cannot be its own parent!')
                    ->withInput();
            }

            // Prepare attribute data
            $attributeData = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ];

            if ($request->filled('description')) {
                $attributeData['description'] = $request->description;
            }

            // Merge with existing attribute data to preserve other fields
            $existingData = $category->attribute_data ?? [];
            $attributeData = array_merge($existingData, $attributeData);

            // Update category
            $category->update([
                'collection_group_id' => $request->collection_group_id,
                'parent_id' => $request->parent_id,
                'type' => $request->type,
                'sort' => $request->sort,
                'attribute_data' => $attributeData,
            ]);

            // Update nested set structure
            $this->updateNestedSet($category);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id)
    {
        try {
            $category = Collection::findOrFail($id);

            // Check if category has subcategories
            $childrenCount = Collection::where('parent_id', $id)->count();

            if ($childrenCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete category with subcategories. Please delete or reassign subcategories first.');
            }

            // Check if category has products
            if ($category->products()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete category with associated products. Please remove products first.');
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    /**
     * Update nested set structure (_lft, _rgt) for the category.
     * This is a simplified implementation. For production, consider using a package like kalnoy/nestedset.
     */
    private function updateNestedSet($category)
    {
        // Get all siblings
        $siblings = Collection::where('collection_group_id', $category->collection_group_id)
            ->where('parent_id', $category->parent_id)
            ->orderBy('id')
            ->get();

        $left = 1;
        if ($category->parent_id) {
            $parent = Collection::find($category->parent_id);
            if ($parent) {
                $left = $parent->_lft + 1;
            }
        }

        foreach ($siblings as $index => $sibling) {
            $sibling->_lft = $left + ($index * 2);
            $sibling->_rgt = $left + ($index * 2) + 1;
            $sibling->save();
        }
    }
}
