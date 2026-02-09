<div class="step-content">
    <h4 class="mb-3">Product Variants</h4>
    <p class="text-muted mb-4">Manage product variants and their stock levels</p>

    <!-- Variant Generation Controls (for Variable products) -->
    <div id="variantControls" style="display: none;">
        <div class="card bg-light mb-4">
            <div class="card-body">
                <h6 class="card-title">Generate Variants</h6>
                <p class="card-text text-muted small">
                    Variants will be automatically generated from your selected options.
                </p>
                <button type="button" class="btn btn-primary btn-sm" id="generateVariantsBtn">
                    <i class="bi bi-magic"></i> Generate All Combinations
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="mb-3" id="bulkActions" style="display: none;">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label small">Bulk Apply Stock</label>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" id="bulkStock" min="0" placeholder="Stock">
                    <button class="btn btn-outline-secondary" type="button" id="applyBulkStock">Apply</button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small">SKU Prefix</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="skuPrefix" placeholder="PREFIX-">
                    <button class="btn btn-outline-secondary" type="button" id="applySkuPrefix">Apply</button>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small">Enable/Disable All</label>
                <div class="btn-group btn-group-sm w-100" role="group">
                    <button type="button" class="btn btn-outline-success" id="enableAllVariants">Enable All</button>
                    <button type="button" class="btn btn-outline-danger" id="disableAllVariants">Disable All</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Variants Table -->
    <div class="table-responsive">
        <table class="table table-hover" id="variantsTable">
            <thead class="table-light">
                <tr>
                    <th style="width: 40%">Variant</th>
                    <th style="width: 25%">SKU</th>
                    <th style="width: 15%">Stock</th>
                    <th style="width: 10%">Enabled</th>
                    <th style="width: 10%">Actions</th>
                </tr>
            </thead>
            <tbody id="variantsTableBody">
                <!-- Variants will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <!-- Empty State -->
    <div class="text-center py-5" id="noVariantsMessage" style="display: none;">
        <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-3">No variants yet. Generate variants or add one manually.</p>
        <button type="button" class="btn btn-primary" id="addVariantManually">
            <i class="bi bi-plus-circle"></i> Add Variant Manually
        </button>
    </div>

    <!-- Hidden field to store existing variants data -->
    <input type="hidden" id="existingVariantsData" value='@json($product->variants ?? [])'>
</div>
