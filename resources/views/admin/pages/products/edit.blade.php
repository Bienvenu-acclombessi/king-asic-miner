@extends('admin.components.app')
@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div>
                <h2>Edit Product</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Edit: {{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Product Images -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Product Images</h4>
                    </div>
                    <div class="card-body">
                        <!-- Current Thumbnail -->
                        @if($product->hasMedia('thumbnail'))
                            <div class="mb-3">
                                <label class="form-label">Current Thumbnail</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $product->getFirstMediaUrl('thumbnail') }}" alt="Thumbnail" class="rounded" style="width: 150px; height: 150px; object-fit: cover;">
                                    <form action="{{ route('admin.products.delete-image', [$product, $product->getFirstMedia('thumbnail')->id]) }}" method="POST" class="ms-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this image?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- Upload New Thumbnail -->
                        <div class="mb-3">
                            <label class="form-label">{{ $product->hasMedia('thumbnail') ? 'Replace' : 'Upload' }} Main Image</label>
                            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                            @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommended size: 800x800px. Max 2MB.</small>
                        </div>

                        <!-- Current Additional Images -->
                        @if($product->hasMedia('images'))
                            <div class="mb-3">
                                <label class="form-label">Current Images ({{ $product->getMedia('images')->count() }})</label>
                                <div class="row g-2">
                                    @foreach($product->getMedia('images') as $media)
                                        <div class="col-md-3">
                                            <div class="position-relative">
                                                <img src="{{ $media->getUrl() }}" alt="Product Image" class="img-fluid rounded" style="width: 100%; height: 150px; object-fit: cover;">
                                                <form action="{{ route('admin.products.delete-image', [$product, $media->id]) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this image?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Upload Additional Images -->
                        <div class="mb-3">
                            <label class="form-label">Add More Images</label>
                            <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" accept="image/*" multiple>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">You can upload multiple images. Max 2MB each.</small>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $product->slug) }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                                   value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" rows="3"
                                      class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="6"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Pricing</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Compare at Price</label>
                                <input type="number" step="0.01" name="compare_price"
                                       class="form-control @error('compare_price') is-invalid @enderror"
                                       value="{{ old('compare_price', $product->compare_price) }}">
                                @error('compare_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cost Price</label>
                                <input type="number" step="0.01" name="cost_price"
                                       class="form-control @error('cost_price') is-invalid @enderror"
                                       value="{{ old('cost_price', $product->cost_price) }}">
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Promotional Pricing</h5>
                        <div class="alert alert-info">
                            <small>Set a sale price and optional date range for promotional pricing. Sale price must be lower than regular price.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sale Price</label>
                                <input type="number" step="0.01" name="sale_price"
                                       class="form-control @error('sale_price') is-invalid @enderror"
                                       value="{{ old('sale_price', $product->sale_price) }}">
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Must be less than regular price</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sale Start Date</label>
                                <input type="datetime-local" name="sale_price_start_date"
                                       class="form-control @error('sale_price_start_date') is-invalid @enderror"
                                       value="{{ old('sale_price_start_date', $product->sale_price_start_date ? $product->sale_price_start_date->format('Y-m-d\TH:i') : '') }}">
                                @error('sale_price_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sale End Date</label>
                                <input type="datetime-local" name="sale_price_end_date"
                                       class="form-control @error('sale_price_end_date') is-invalid @enderror"
                                       value="{{ old('sale_price_end_date', $product->sale_price_end_date ? $product->sale_price_end_date->format('Y-m-d\TH:i') : '') }}">
                                @error('sale_price_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Inventory</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="manage_stock" id="manageStock" value="1"
                                       {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manageStock">
                                    <strong>Manage Stock</strong>
                                    <br><small class="text-muted">Enable stock quantity tracking for this product</small>
                                </label>
                            </div>
                        </div>

                        <div id="stockQuantitySection" class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="stock_quantity" id="stockQuantity"
                                       class="form-control @error('stock_quantity') is-invalid @enderror"
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Low Stock Threshold</label>
                                <input type="number" name="low_stock_threshold"
                                       class="form-control @error('low_stock_threshold') is-invalid @enderror"
                                       value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}">
                                @error('low_stock_threshold')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock Status <span class="text-danger">*</span></label>
                            <select name="stock_status" class="form-select @error('stock_status') is-invalid @enderror" required>
                                <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                <option value="on_backorder" {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                            </select>
                            @error('stock_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="backorders_allowed" id="backordersAllowed" value="1"
                                       {{ old('backorders_allowed', $product->backorders_allowed) ? 'checked' : '' }}>
                                <label class="form-check-label" for="backordersAllowed">
                                    Allow Backorders
                                    <br><small class="text-muted">Allow purchases when out of stock</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="sold_individually" id="soldIndividually" value="1"
                                       {{ old('sold_individually', $product->sold_individually) ? 'checked' : '' }}>
                                <label class="form-check-label" for="soldIndividually">
                                    Sold Individually
                                    <br><small class="text-muted">Limit purchase to 1 item per order</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Shipping</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" name="weight"
                                       class="form-control @error('weight') is-invalid @enderror"
                                       value="{{ old('weight', $product->weight) }}">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Length (cm)</label>
                                <input type="number" step="0.01" name="length"
                                       class="form-control @error('length') is-invalid @enderror"
                                       value="{{ old('length', $product->length) }}">
                                @error('length')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Width (cm)</label>
                                <input type="number" step="0.01" name="width"
                                       class="form-control @error('width') is-invalid @enderror"
                                       value="{{ old('width', $product->width) }}">
                                @error('width')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Height (cm)</label>
                                <input type="number" step="0.01" name="height"
                                       class="form-control @error('height') is-invalid @enderror"
                                       value="{{ old('height', $product->height) }}">
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">SEO</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title"
                                   class="form-control @error('meta_title') is-invalid @enderror"
                                   value="{{ old('meta_title', $product->meta_title) }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" rows="3"
                                      class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $product->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_keywords"
                                   class="form-control @error('meta_keywords') is-invalid @enderror"
                                   value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                   placeholder="Separate keywords with commas">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Variants Manager -->
                @include('admin.pages.products.partials.variants-manager')

                <!-- Related Products -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Related Products</h4>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="relatedProductsAccordion">
                            <!-- Upsells -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#upsellsCollapse">
                                        Upsells (Premium/Upgrade Products)
                                    </button>
                                </h2>
                                <div id="upsellsCollapse" class="accordion-collapse collapse" data-bs-parent="#relatedProductsAccordion">
                                    <div class="accordion-body">
                                        <select name="upsells[]" class="form-select select2-upsells" multiple>
                                            @foreach($allProducts as $p)
                                                <option value="{{ $p->id }}" {{ $product->upsells->contains($p->id) ? 'selected' : '' }}>
                                                    {{ $p->name }} ({{ $p->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Products to suggest as upgrades or premium alternatives</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Cross-sells -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#crossSellsCollapse">
                                        Cross-sells (Complementary Products)
                                    </button>
                                </h2>
                                <div id="crossSellsCollapse" class="accordion-collapse collapse" data-bs-parent="#relatedProductsAccordion">
                                    <div class="accordion-body">
                                        <select name="cross_sells[]" class="form-select select2-cross-sells" multiple>
                                            @foreach($allProducts as $p)
                                                <option value="{{ $p->id }}" {{ $product->crossSells->contains($p->id) ? 'selected' : '' }}>
                                                    {{ $p->name }} ({{ $p->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Products that complement this one (e.g., accessories)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Related -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#relatedCollapse">
                                        Related Products (Similar Items)
                                    </button>
                                </h2>
                                <div id="relatedCollapse" class="accordion-collapse collapse" data-bs-parent="#relatedProductsAccordion">
                                    <div class="accordion-body">
                                        <select name="related_products[]" class="form-select select2-related" multiple>
                                            @foreach($allProducts as $p)
                                                <option value="{{ $p->id }}" {{ $product->relatedProducts->contains($p->id) ? 'selected' : '' }}>
                                                    {{ $p->name }} ({{ $p->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Similar products customers might be interested in</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $product->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label">Featured Product</label>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Category</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                    <option value="{{ $tag->id }}" {{ $product->tags->contains($tag->id) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select one or more tags for this product</small>
                        </div>
                    </div>
                </div>

                <!-- Promotions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Promotions</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Active Promotions</label>
                            <select name="promotions[]" class="form-select select2-promotions @error('promotions') is-invalid @enderror" multiple>
                                @foreach($promotions as $promotion)
                                    <option value="{{ $promotion->id }}" {{ $product->promotions->contains($promotion->id) ? 'selected' : '' }}>
                                        {{ $promotion->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('promotions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Attach this product to active promotions</small>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">Delete Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Toggle stock quantity section based on manage_stock checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const manageStockCheckbox = document.getElementById('manageStock');
        const stockQuantitySection = document.getElementById('stockQuantitySection');
        const stockQuantityInput = document.getElementById('stockQuantity');

        function toggleStockQuantity() {
            if (manageStockCheckbox.checked) {
                stockQuantitySection.style.display = 'flex';
                stockQuantityInput.required = true;
            } else {
                stockQuantitySection.style.display = 'none';
                stockQuantityInput.required = false;
            }
        }

        manageStockCheckbox.addEventListener('change', toggleStockQuantity);
        toggleStockQuantity(); // Initial state

        // Phase 3: Initialize Select2 for all multi-selects
        $('.select2-tags').select2({
            placeholder: 'Select tags',
            allowClear: true
        });

        $('.select2-promotions').select2({
            placeholder: 'Select promotions',
            allowClear: true
        });

        $('.select2-upsells').select2({
            placeholder: 'Select upsell products',
            allowClear: true
        });

        $('.select2-cross-sells').select2({
            placeholder: 'Select cross-sell products',
            allowClear: true
        });

        $('.select2-related').select2({
            placeholder: 'Select related products',
            allowClear: true
        });
    });
</script>
@endpush
@endsection
