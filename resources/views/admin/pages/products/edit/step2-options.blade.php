<div class="step-content">
    <h4 class="mb-3">Product Options</h4>
    <p class="text-muted mb-4">Configure options for your product (e.g., Size, Color, Warranty)</p>

    <!-- Options Selection -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label mb-0">Select Options</label>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createProductOptionModal">
                <i class="bi bi-plus-circle me-1"></i> Create New Option
            </button>
        </div>
        <select class="form-select" id="optionSelector">
            <option value="">Choose an option to add...</option>
            @foreach($productOptions as $option)
                @php
                    $optionName = $option->label ?? $option->name ?? (is_array($option->attribute_data) ? ($option->attribute_data['name'] ?? '') : '');
                    $optionName = is_array($optionName) ? ($optionName['en'] ?? $optionName[0] ?? '') : $optionName;
                @endphp
                <option value="{{ $option->id }}" data-option="{{ json_encode($option) }}">
                    {{ $optionName ?: 'Option #' . $option->id }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Selected Options List -->
    <div id="selectedOptionsList">
        @if($product->productOptions && $product->productOptions->count() > 0)
            <div class="alert alert-info" id="existingOptionsAlert">
                <i class="bi bi-info-circle me-2"></i>
                This product has {{ $product->productOptions->count() }} option(s) configured. You can add or remove options below.
            </div>
        @else
            <div class="alert alert-warning" id="noOptionsAlert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                No options selected yet. Add options above or skip this step for simple products.
            </div>
        @endif
    </div>

    <!-- Options will be dynamically added here -->
    <div id="optionsContainer">
        @foreach($product->productOptions ?? [] as $productOption)
            @php
                $optionName = $productOption->label ?? $productOption->name ?? 'Option';
                $optionValues = $productOption->values ?? [];
            @endphp
            <!-- Existing options will be loaded via JavaScript -->
        @endforeach
    </div>

    <!-- Hidden field to store existing options data -->
    <input type="hidden" id="existingOptionsData" value='@json($product->productOptions ?? [])'>
</div>
