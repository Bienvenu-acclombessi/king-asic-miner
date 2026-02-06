<div class="step-content">
    <h4 class="mb-3">Product Options</h4>
    <p class="text-muted mb-4">Configure options for your product (e.g., Size, Color, Warranty)</p>

    <!-- Options Selection -->
    <div class="mb-4">
        <label class="form-label">Select Options</label>
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
        <div class="alert alert-warning" id="noOptionsAlert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            No options selected yet. Add options above or skip this step for simple products.
        </div>
    </div>

    <!-- Options will be dynamically added here -->
    <div id="optionsContainer"></div>
</div>
