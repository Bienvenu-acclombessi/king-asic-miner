<!-- Product Variants Manager -->
<div class="card mb-4" id="variantsCard">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Product Variations</h4>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="enableVariants" {{ (isset($product) && $product->variants->count() > 0) ? 'checked' : '' }}>
                <label class="form-check-label" for="enableVariants">
                    Enable Variations
                </label>
            </div>
        </div>
    </div>
    <div class="card-body" id="variantsBody" style="{{ (isset($product) && $product->variants->count() > 0) ? '' : 'display:none;' }}">

        <!-- Step 1: Select Attributes -->
        <div class="mb-4">
            <h5 class="mb-3">Step 1: Select Attributes</h5>
            <p class="text-muted small">Choose which attributes to use for creating variations (e.g., Hashrate, Color)</p>

            <div class="row" id="attributesSelection">
                @foreach($attributes as $attribute)
                    <div class="col-md-4 mb-3">
                        <div class="card attribute-card {{ (isset($product) && $product->variants->flatMap->attributeValues->pluck('product_attribute_id')->contains($attribute->id)) ? 'border-primary' : '' }}"
                             data-attribute-id="{{ $attribute->id }}">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input attribute-checkbox"
                                           type="checkbox"
                                           value="{{ $attribute->id }}"
                                           id="attr_{{ $attribute->id }}"
                                           {{ (isset($product) && $product->variants->flatMap->attributeValues->pluck('product_attribute_id')->contains($attribute->id)) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="attr_{{ $attribute->id }}">
                                        {{ $attribute->name }}
                                    </label>
                                </div>
                                <div class="attribute-values mt-2" style="display:{{ (isset($product) && $product->variants->flatMap->attributeValues->pluck('product_attribute_id')->contains($attribute->id)) ? 'block' : 'none' }};">
                                    <small class="text-muted d-block mb-2">Select values:</small>
                                    @foreach($attribute->values as $value)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input value-checkbox"
                                                   type="checkbox"
                                                   value="{{ $value->id }}"
                                                   data-attribute-id="{{ $attribute->id }}"
                                                   data-value-text="{{ $value->value }}"
                                                   id="val_{{ $value->id }}"
                                                   {{ (isset($product) && $product->variants->flatMap->attributeValues->pluck('id')->contains($value->id)) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="val_{{ $value->id }}">
                                                {{ $value->value }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <hr class="my-4">

        <!-- Step 2: Generate or Manage Variations -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0">Step 2: Manage Variations</h5>
                    <p class="text-muted small mb-0">Generated combinations based on selected attributes</p>
                </div>
                <button type="button" class="btn btn-primary" id="generateVariants">
                    <i class="bi bi-arrow-repeat me-1"></i> Generate Variations
                </button>
            </div>

            <div id="variantsTableContainer" style="display:{{ (isset($product) && $product->variants->count() > 0) ? 'block' : 'none' }};">
                <div class="table-responsive">
                    <table class="table table-bordered" id="variantsTable">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 5%;">
                                    <input type="checkbox" id="selectAllVariants" title="Select all">
                                </th>
                                <th style="width: 25%;">Variation</th>
                                <th style="width: 15%;">SKU</th>
                                <th style="width: 15%;">Price +/-</th>
                                <th style="width: 10%;">Stock</th>
                                <th style="width: 10%;">Active</th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="variantsTableBody">
                            @if(isset($product) && $product->variants->count() > 0)
                                @foreach($product->variants as $index => $variant)
                                    <tr class="variant-row" data-variant-index="{{ $index }}">
                                        <td>
                                            <input type="checkbox" class="variant-checkbox">
                                        </td>
                                        <td>
                                            <input type="hidden" name="variants[{{ $index }}][name]" value="{{ $variant->name }}">
                                            <input type="hidden" name="variants[{{ $index }}][attribute_value_ids]" value="{{ $variant->attributeValues->pluck('id')->join(',') }}">
                                            <strong>{{ $variant->display_name }}</strong>
                                        </td>
                                        <td>
                                            <input type="text" name="variants[{{ $index }}][sku]" class="form-control form-control-sm"
                                                   value="{{ $variant->sku }}" placeholder="SKU-{{ $index + 1 }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="variants[{{ $index }}][additional_price]"
                                                   class="form-control form-control-sm variant-price" value="{{ $variant->additional_price }}" placeholder="0.00">
                                        </td>
                                        <td>
                                            <input type="number" name="variants[{{ $index }}][quantity]"
                                                   class="form-control form-control-sm variant-stock" value="{{ $variant->quantity }}" placeholder="0">
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" name="variants[{{ $index }}][is_active]" value="1"
                                                       class="form-check-input" {{ $variant->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-variant" onclick="variantsManager.deleteVariant({{ $index }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-outline-danger" id="deleteSelectedVariants">
                        <i class="bi bi-trash me-1"></i> Delete Selected
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="applyBulkPrice">
                        <i class="bi bi-cash me-1"></i> Apply Price to All
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="applyBulkStock">
                        <i class="bi bi-box me-1"></i> Apply Stock to All
                    </button>
                </div>
            </div>

            <div id="noVariantsMessage" class="alert alert-info" style="display:{{ (isset($product) && $product->variants->count() > 0) ? 'none' : 'block' }};">
                <i class="bi bi-info-circle me-2"></i>
                Select attributes and click "Generate Variations" to create product variations.
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
.attribute-card {
    transition: all 0.2s;
    cursor: pointer;
}
.attribute-card:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}
.attribute-card.border-primary {
    background-color: #f8f9ff;
}
.variant-row.deleted {
    opacity: 0.5;
    text-decoration: line-through;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/admin/js/variants-manager.js') }}"></script>
@endpush
