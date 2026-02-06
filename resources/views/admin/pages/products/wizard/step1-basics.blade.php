<div class="step-content">
    <h4 class="mb-3">Basic Information</h4>
    <p class="text-muted mb-4">Enter the basic details about your product</p>

    <div class="row">
        <!-- Product Name -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Product Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" id="name" required>
            <div class="invalid-feedback"></div>
        </div>

        <!-- Slug -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="slug" id="slug" required>
            <small class="form-text text-muted">Auto-generated from product name</small>
            <div class="invalid-feedback"></div>
        </div>

        <!-- Product Type -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Product Type <span class="text-danger">*</span></label>
            <select class="form-select" name="product_type_id" id="product_type_id" required>
                <option value="">Select Product Type</option>
                @foreach($productTypes as $type)
                    @php
                        $typeName = $type->name ?? (is_array($type->attribute_data) ? ($type->attribute_data['name'] ?? '') : '');
                        $typeName = is_array($typeName) ? ($typeName['en'] ?? $typeName[0] ?? '') : $typeName;
                    @endphp
                    <option value="{{ $type->id }}" data-type="{{ strtolower($typeName) }}">
                        {{ $typeName ?: 'Type #' . $type->id }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <!-- Brand -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Brand</label>
            <select class="form-select" name="brand_id" id="brand_id">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    @php
                        $brandName = $brand->name ?? (is_array($brand->attribute_data) ? ($brand->attribute_data['name'] ?? '') : '');
                        $brandName = is_array($brandName) ? ($brandName['en'] ?? $brandName[0] ?? '') : $brandName;
                    @endphp
                    <option value="{{ $brand->id }}">{{ $brandName ?: 'Brand #' . $brand->id }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select" name="status" id="status" required>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
        </div>

        <!-- Short Description -->
        <div class="col-12 mb-3">
            <label class="form-label">Short Description</label>
            <textarea class="form-control" name="short_description" id="short_description" rows="2"></textarea>
        </div>

        <!-- Description -->
        <div class="col-12 mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" rows="4"></textarea>
        </div>
    </div>

    <!-- Product Type Info Alert -->
    <div class="alert alert-info mt-3" id="productTypeInfo" style="display: none;">
        <i class="bi bi-info-circle me-2"></i>
        <span id="productTypeInfoText"></span>
    </div>
</div>
