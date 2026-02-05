<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index(Request $request)
    {
        $query = Tag::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('value', 'LIKE', '%' . $search . '%');
        }

        $tags = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pages.tags.index', compact('tags'));
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:255|unique:tags,value',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'value' => $request->value,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('tags', 'public');
                $data['image'] = $imagePath;
            }

            Tag::create($data);

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating tag: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified tag (AJAX).
     */
    public function edit($id)
    {
        try {
            $tag = Tag::findOrFail($id);

            return response()->json([
                'success' => true,
                'tag' => $tag
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found'
            ], 404);
        }
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:255|unique:tags,value,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $tag = Tag::findOrFail($id);

            $data = [
                'value' => $request->value,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($tag->image && Storage::disk('public')->exists($tag->image)) {
                    Storage::disk('public')->delete($tag->image);
                }

                $imagePath = $request->file('image')->store('tags', 'public');
                $data['image'] = $imagePath;
            }

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image == '1') {
                if ($tag->image && Storage::disk('public')->exists($tag->image)) {
                    Storage::disk('public')->delete($tag->image);
                }
                $data['image'] = null;
            }

            $tag->update($data);

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating tag: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified tag.
     */
    public function destroy($id)
    {
        try {
            $tag = Tag::findOrFail($id);

            // Delete image if exists
            if ($tag->image && Storage::disk('public')->exists($tag->image)) {
                Storage::disk('public')->delete($tag->image);
            }

            $tag->delete();

            return redirect()->route('admin.tags.index')
                ->with('success', 'Tag deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting tag: ' . $e->getMessage());
        }
    }
}
