<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products\Attribute;
use App\Models\Products\AttributeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of attributes.
     */
    public function index()
    {
        $attributes = Attribute::with('attributeGroup')
            ->orderBy('position')
            ->paginate(20);

        return view('admin.pages.attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new attribute.
     */
    public function create()
    {
        $attributeGroups = AttributeGroup::orderBy('position')->get();

        return view('admin.pages.attributes.create', compact('attributeGroups'));
    }

    /**
     * Store a newly created attribute.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'attribute_group_id' => 'nullable|exists:attribute_groups,id',
            'attribute_type' => 'required|string',
            'type' => 'required|string',
            'section' => 'nullable|string',
            'required' => 'boolean',
            'system' => 'boolean',
            'position' => 'nullable|integer',
            'description' => 'nullable|string',
            'configuration' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Generate handle from name if not provided
        $handle = $request->handle ?? Str::slug($request->name);

        // Get or create default attribute group if none selected
        $attributeGroupId = $request->attribute_group_id;
        if (!$attributeGroupId) {
            $defaultGroup = AttributeGroup::firstOrCreate(
                ['handle' => 'default'],
                [
                    'attributable_type' => 'product',
                    'name' => ['en' => 'Default'],
                    'position' => 999,
                ]
            );
            $attributeGroupId = $defaultGroup->id;
        }

        $attribute = Attribute::create([
            'name' => ['en' => $request->name],
            'handle' => $handle,
            'attribute_group_id' => $attributeGroupId,
            'attribute_type' => $request->attribute_type,
            'type' => $request->type,
            'section' => $request->section ?? 'general',
            'required' => $request->has('required') ? (bool)$request->required : false,
            'system' => $request->has('system') ? (bool)$request->system : false,
            'position' => $request->position ?? 0,
            'description' => $request->description ? ['en' => $request->description] : null,
            'configuration' => $request->configuration ?? [],
            'default_value' => $request->default_value,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute created successfully',
                'attribute' => $attribute->load('attributeGroup'),
            ]);
        }

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully');
    }

    /**
     * Show the form for editing the specified attribute.
     */
    public function edit(Attribute $attribute)
    {
        $attributeGroups = AttributeGroup::orderBy('position')->get();

        return view('admin.pages.attributes.edit', compact('attribute', 'attributeGroups'));
    }

    /**
     * Update the specified attribute.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'attribute_group_id' => 'required|exists:attribute_groups,id',
            'attribute_type' => 'required|string',
            'type' => 'required|string',
            'section' => 'nullable|string',
            'required' => 'boolean',
            'system' => 'boolean',
            'position' => 'nullable|integer',
            'description' => 'nullable|string',
            'configuration' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $attribute->update([
            'name' => ['en' => $request->name],
            'attribute_group_id' => $request->attribute_group_id,
            'attribute_type' => $request->attribute_type,
            'type' => $request->type,
            'section' => $request->section ?? 'general',
            'required' => $request->has('required') ? (bool)$request->required : false,
            'system' => $request->has('system') ? (bool)$request->system : false,
            'position' => $request->position ?? 0,
            'description' => $request->description ? ['en' => $request->description] : null,
            'configuration' => $request->configuration ?? [],
            'default_value' => $request->default_value,
        ]);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully');
    }

    /**
     * Remove the specified attribute.
     */
    public function destroy(Attribute $attribute)
    {
        if ($attribute->system) {
            return back()->with('error', 'Cannot delete system attribute');
        }

        $attribute->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute deleted successfully');
    }
}
