@extends('admin.components.app')
@section('title', 'Add New Product')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Add New Product</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                            <li class="breadcrumb-item active">Add New</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Main Content Column -->
            <div class="col-lg-8">

                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="productName"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="productSlug"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug') }}" placeholder="Auto-generated if left empty">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to auto-generate from product name</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" rows="3"
                                      class="form-control @error('short_description') is-invalid @enderror"
                                      placeholder="Brief product description for listings">{{ old('short_description') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="8" id="productDescription"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Detailed product description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Data Tabs -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Product Data</h4>
                    </div>
                    <div class="card-body">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs mb-4" id="productDataTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                        data-bs-target="#general" type="button" role="tab">
                                    General
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="inventory-tab" data-bs-toggle="tab"
                                        data-bs-target="#inventory" type="button" role="tab">
                                    Inventory
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab"
                                        data-bs-target="#shipping" type="button" role="tab">
                                    Shipping
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="attributes-tab" data-bs-toggle="tab"
                                        data-bs-target="#attributes" type="button" role="tab">
                                    Attributes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="variants-tab" data-bs-toggle="tab"
                                        data-bs-target="#variants" type="button" role="tab">
                                    Variants
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="advanced-tab" data-bs-toggle="tab"
                                        data-bs-target="#advanced" type="button" role="tab">
                                    Advanced
                                </button>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content" id="productDataTabsContent">

                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Product Type <span class="text-danger">*</span></label>
                                        <select name="product_type_id" id="productTypeId"
                                                class="form-select @error('product_type_id') is-invalid @enderror" required>
                                            <option value="">Select Product Type</option>
                                            @foreach($productTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('product_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ is_array($type->name) ? ($type->name['en'] ?? 'N/A') : $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('product_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Brand</label>
                                        <select name="brand_id" id="brandId"
                                                class="form-select @error('brand_id') is-invalid @enderror">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h5 class="mb-3">Product Options</h5>
                                <p class="text-muted small">Select product options to create variants (e.g., Size, Color)</p>

                                <div id="productOptionsContainer">
                                    @foreach($productOptions as $option)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input product-option-checkbox" type="checkbox"
                                                   name="product_options[]" value="{{ $option->id }}"
                                                   id="option_{{ $option->id }}"
                                                   data-option-name="{{ is_array($option->name) ? ($option->name['en'] ?? 'N/A') : $option->name }}"
                                                   data-option-values='@json($option->values)'>
                                            <label class="form-check-label" for="option_{{ $option->id }}">
                                                <strong>{{ is_array($option->name) ? ($option->name['en'] ?? 'N/A') : $option->name }}</strong>
                                                <small class="text-muted">
                                                    ({{ $option->values->count() }} values:
                                                    {{ $option->values->take(3)->map(function($v) {
                                                        return is_array($v->name) ? ($v->name['en'] ?? 'N/A') : $v->name;
                                                    })->implode(', ') }}{{ $option->values->count() > 3 ? '...' : '' }})
                                                </small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Inventory Tab -->
                            <div class="tab-pane fade" id="inventory" role="tabpanel">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Note:</strong> Inventory management for individual variants will be handled in the Variants tab.
                                </div>
                                <p class="text-muted">Stock tracking is managed at the variant level for more granular control.</p>
                            </div>

                            <!-- Shipping Tab -->
                            <div class="tab-pane fade" id="shipping" role="tabpanel">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Note:</strong> Shipping details for individual variants will be handled in the Variants tab.
                                </div>
                                <p class="text-muted">Dimensions and weight are tracked at the variant level to accommodate different sizes.</p>
                            </div>

                            <!-- Attributes Tab -->
                            <div class="tab-pane fade" id="attributes" role="tabpanel">
                                <h5 class="mb-3">Custom Attributes</h5>
                                <p class="text-muted small">Add custom attributes to this product</p>

                                <div id="attributesContainer" class="mb-3">
                                    <!-- Attributes will be added dynamically -->
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary" id="addAttributeBtn">
                                    <i class="bi bi-plus-circle"></i> Add Attribute
                                </button>

                                <input type="hidden" name="attributes" id="attributesJson">

                                <hr class="my-4">
                                <h6>Available Attribute Types:</h6>
                                <div class="row">
                                    @foreach($attributes->groupBy('attributeGroup.name') as $groupName => $attrs)
                                        <div class="col-md-6 mb-2">
                                            <strong>{{ is_array($groupName) ? ($groupName['en'] ?? 'General') : ($groupName ?? 'General') }}</strong>
                                            <ul class="small text-muted">
                                                @foreach($attrs->take(5) as $attr)
                                                    <li>{{ is_array($attr->name) ? ($attr->name['en'] ?? $attr->handle) : $attr->name }} ({{ $attr->attribute_type }})</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Variants Tab -->
                            <div class="tab-pane fade" id="variants" role="tabpanel">
                                <div id="variantsManager">
                                    <div id="noOptionsMessage" class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <strong>No product options selected</strong>
                                        <p class="mb-0">Please select product options in the General tab to generate variants automatically.</p>
                                    </div>

                                    <div id="variantsContainer" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h5 class="mb-1">Product Variants</h5>
                                                <p class="text-muted small mb-0">Manage pricing, stock, and details for each variant</p>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary" id="generateVariantsBtn">
                                                <i class="bi bi-arrow-repeat"></i> Generate Variants
                                            </button>
                                        </div>

                                        <div id="variantsList" class="table-responsive">
                                            <!-- Variants will be generated here -->
                                        </div>

                                        <input type="hidden" name="variants" id="variantsJson">
                                    </div>
                                </div>
                            </div>

                            <!-- Advanced Tab -->
                            <div class="tab-pane fade" id="advanced" role="tabpanel">
                                <h5 class="mb-3">SEO Settings</h5>

                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title"
                                           class="form-control @error('meta_title') is-invalid @enderror"
                                           value="{{ old('meta_title') }}"
                                           placeholder="Leave empty to use product name">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" rows="3"
                                              class="form-control @error('meta_description') is-invalid @enderror"
                                              placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" name="meta_keywords"
                                           class="form-control @error('meta_keywords') is-invalid @enderror"
                                           value="{{ old('meta_keywords') }}"
                                           placeholder="keyword1, keyword2, keyword3">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">

                <!-- Publish -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Publish</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Publish Product
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="document.querySelector('[name=status]').value='draft'; document.getElementById('productForm').submit();">
                                <i class="bi bi-save"></i> Save as Draft
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Collections (Categories) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Collections</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Collections</label>
                            <select name="collections[]" class="form-select select2-collections @error('collections') is-invalid @enderror" multiple>
                                @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}">
                                        {{ is_array($collection->attribute_data) && isset($collection->attribute_data['name']) ? $collection->attribute_data['name'] : 'Collection #'.$collection->id }}
                                    </option>
                                    @if($collection->children)
                                        @foreach($collection->children as $child)
                                            <option value="{{ $child->id }}">
                                                &nbsp;&nbsp;— {{ is_array($child->attribute_data) && isset($child->attribute_data['name']) ? $child->attribute_data['name'] : 'Collection #'.$child->id }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                            @error('collections')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select one or more collections</small>
                        </div>
                    </div>
                </div>

                <!-- Tags -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Tags</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Tags</label>
                            <select name="tags[]" class="form-select select2-tags @error('tags') is-invalid @enderror" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->value }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select tags for better organization</small>
                        </div>
                    </div>
                </div>

                <!-- Customer Groups -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Visibility</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Customer Groups</label>
                            <select name="customer_groups[]" class="form-select select2-customer-groups @error('customer_groups') is-invalid @enderror" multiple>
                                @foreach($customerGroups as $group)
                                    <option value="{{ $group->id }}" selected>{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('customer_groups')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Product visible to these customer groups</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .variant-card {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #f8f9fa;
    }
    .variant-header {
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #dee2e6;
    }
    .attribute-item {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        background-color: #fff;
    }
    .select2-container {
        width: 100% !important;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('admin/js/product-manager.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Initialize Select2
    $('.select2-collections').select2({
        placeholder: 'Select collections',
        allowClear: true
    });

    $('.select2-tags').select2({
        placeholder: 'Select tags',
        allowClear: true,
        tags: true
    });

    $('.select2-customer-groups').select2({
        placeholder: 'Select customer groups',
        allowClear: true
    });

    // Auto-generate slug from name
    const productNameInput = document.getElementById('productName');
    const productSlugInput = document.getElementById('productSlug');

    productNameInput.addEventListener('input', function() {
        if (!productSlugInput.value || productSlugInput.dataset.autoGenerated === 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            productSlugInput.value = slug;
            productSlugInput.dataset.autoGenerated = 'true';
        }
    });

    productSlugInput.addEventListener('input', function() {
        if (this.value) {
            this.dataset.autoGenerated = 'false';
        }
    });

    // Initialize Product Manager
    const productManager = new ProductManager({
        customerGroups: @json($customerGroups),
        productOptions: @json($productOptions),
        attributes: @json($attributes)
    });

    // Form submission handler
    document.getElementById('productForm').addEventListener('submit', function(e) {
        // Update hidden fields with JSON data
        productManager.updateFormData();
    });

});
</script>
@endpush
@endsection
