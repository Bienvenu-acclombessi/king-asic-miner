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

    <!-- Product Attributes Section -->
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">Product Attributes</h5>
                <p class="text-muted small mb-0">Add descriptive attributes to your product (e.g., Weight, Dimensions, Material)</p>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createAttributeModal">
                <i class="bi bi-plus-circle"></i> New Attribute
            </button>
        </div>

        <div id="attributesContainer">
            @if(isset($attributes) && $attributes->count() > 0)
                @foreach($attributes as $attribute)
                    @php
                        $attrName = is_array($attribute->name) ? ($attribute->name['en'] ?? $attribute->name[0] ?? '') : $attribute->name;
                        $attrType = $attribute->type;
                        $isRequired = $attribute->required;
                        $config = $attribute->configuration ?? [];
                        $attrDescription = is_array($attribute->description) ? ($attribute->description['en'] ?? $attribute->description[0] ?? '') : $attribute->description;
                    @endphp

                    <div class="mb-3" data-attribute-id="{{ $attribute->id }}">
                        <label class="form-label">
                            {{ $attrName }}
                            @if($isRequired) <span class="text-danger">*</span> @endif
                        </label>

                        @if($attrDescription)
                            <small class="text-muted d-block mb-1">{{ $attrDescription }}</small>
                        @endif

                        @if($attrType === 'text' || $attrType === 'string')
                            <input type="text"
                                   class="form-control attribute-input"
                                   data-attribute-id="{{ $attribute->id }}"
                                   data-attribute-handle="{{ $attribute->handle }}"
                                   placeholder="Enter {{ strtolower($attrName) }}"
                                   {{ $isRequired ? 'required' : '' }}>
                        @elseif($attrType === 'textarea')
                            <textarea class="form-control attribute-input"
                                      data-attribute-id="{{ $attribute->id }}"
                                      data-attribute-handle="{{ $attribute->handle }}"
                                      rows="2"
                                      placeholder="Enter {{ strtolower($attrName) }}"
                                      {{ $isRequired ? 'required' : '' }}></textarea>
                        @elseif($attrType === 'number' || $attrType === 'integer' || $attrType === 'decimal')
                            <input type="number"
                                   class="form-control attribute-input"
                                   data-attribute-id="{{ $attribute->id }}"
                                   data-attribute-handle="{{ $attribute->handle }}"
                                   step="{{ $attrType === 'decimal' ? '0.01' : '1' }}"
                                   placeholder="Enter {{ strtolower($attrName) }}"
                                   {{ $isRequired ? 'required' : '' }}>
                        @elseif($attrType === 'select' || $attrType === 'dropdown')
                            <select class="form-select attribute-input"
                                    data-attribute-id="{{ $attribute->id }}"
                                    data-attribute-handle="{{ $attribute->handle }}"
                                    {{ $isRequired ? 'required' : '' }}>
                                <option value="">Select {{ strtolower($attrName) }}</option>
                                @if(isset($config['options']) && is_array($config['options']))
                                    @foreach($config['options'] as $option)
                                        <option value="{{ is_array($option) ? ($option['value'] ?? $option['label']) : $option }}">
                                            {{ is_array($option) ? ($option['label'] ?? $option['value']) : $option }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        @elseif($attrType === 'boolean' || $attrType === 'checkbox')
                            <div class="form-check form-switch">
                                <input class="form-check-input attribute-input"
                                       type="checkbox"
                                       data-attribute-id="{{ $attribute->id }}"
                                       data-attribute-handle="{{ $attribute->handle }}"
                                       value="1">
                                <label class="form-check-label">
                                    Enable {{ strtolower($attrName) }}
                                </label>
                            </div>
                        @elseif($attrType === 'date')
                            <input type="date"
                                   class="form-control attribute-input"
                                   data-attribute-id="{{ $attribute->id }}"
                                   data-attribute-handle="{{ $attribute->handle }}"
                                   {{ $isRequired ? 'required' : '' }}>
                        @else
                            <input type="text"
                                   class="form-control attribute-input"
                                   data-attribute-id="{{ $attribute->id }}"
                                   data-attribute-handle="{{ $attribute->handle }}"
                                   placeholder="Enter {{ strtolower($attrName) }}"
                                   {{ $isRequired ? 'required' : '' }}>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-muted small mb-0">No attributes configured for products yet.</p>
            @endif
        </div>
    </div>

    <!-- Minable Coins Section -->
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">Minable Coins</h5>
                <p class="text-muted small mb-0">Select which cryptocurrencies this product can mine</p>
            </div>
        </div>

        <div id="minableCoinsContainer" class="row g-3">
            @if(isset($minableCoins) && $minableCoins->count() > 0)
                @foreach($minableCoins as $coin)
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input minable-coin-checkbox"
                                           type="checkbox"
                                           name="minable_coins[]"
                                           value="{{ $coin->id }}"
                                           id="coin-{{ $coin->id }}"
                                           data-coin-symbol="{{ $coin->symbol }}">
                                    <label class="form-check-label w-100" for="coin-{{ $coin->id }}">
                                        <div class="d-flex align-items-center">
                                            @if($coin->logo_url)
                                                <img src="{{ $coin->logo_url }}" alt="{{ $coin->name }}" style="width: 30px; height: 30px; object-fit: contain;" class="me-2">
                                            @else
                                                <div style="width: 30px; height: 30px; background-color: {{ $coin->color ?? '#ccc' }}; border-radius: 50%;" class="me-2"></div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $coin->symbol }}</div>
                                                <small class="text-muted">{{ $coin->name }}</small>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">{{ $coin->algorithm }}</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-muted small mb-0">No minable coins configured yet. <a href="{{ route('admin.minable-coins.index') }}" target="_blank">Add coins here</a>.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Type Info Alert -->
    <div class="alert alert-info mt-3" id="productTypeInfo" style="display: none;">
        <i class="bi bi-info-circle me-2"></i>
        <span id="productTypeInfoText"></span>
    </div>
</div>
