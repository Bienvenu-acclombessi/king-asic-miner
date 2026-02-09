@extends('admin.components.app')

@section('title', 'Create Product - Wizard')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/css/product-wizard.css') }}">
<style>
    /* Fix modal z-index to appear above sidebar */
    .modal-backdrop.show {
        z-index: 9998 !important;
    }
    #createAttributeModal {
        z-index: 9999 !important;
    }
    #createAttributeModal .modal-dialog {
        z-index: 10000 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Create New Product</h2>
                    <p class="text-muted mb-0">Follow the steps to create your product</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Products
                </a>
            </div>

            <!-- Progress Bar -->
            @include('admin.pages.products.wizard.components.progress-bar')

            <!-- Wizard Form -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form id="wizardForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1: Basics -->
                        <div class="wizard-step" data-step="1">
                            @include('admin.pages.products.wizard.step1-basics')
                        </div>

                        <!-- Step 2: Options -->
                        <div class="wizard-step" data-step="2" style="display: none;">
                            @include('admin.pages.products.wizard.step2-options')
                        </div>

                        <!-- Step 3: Variants -->
                        <div class="wizard-step" data-step="3" style="display: none;">
                            @include('admin.pages.products.wizard.step3-variants')
                        </div>

                        <!-- Step 4: Pricing -->
                        <div class="wizard-step" data-step="4" style="display: none;">
                            @include('admin.pages.products.wizard.step4-pricing')
                        </div>

                        <!-- Step 5: Media -->
                        <div class="wizard-step" data-step="5" style="display: none;">
                            @include('admin.pages.products.wizard.step5-media')
                        </div>

                        <!-- Step 6: Finalize -->
                        <div class="wizard-step" data-step="6" style="display: none;">
                            @include('admin.pages.products.wizard.step6-finalize')
                        </div>

                        <!-- Navigation -->
                        @include('admin.pages.products.wizard.components.step-navigation')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast for auto-save notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="autoSaveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Draft Saved</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Your changes have been saved automatically.
        </div>
    </div>
</div>

<!-- Create Product Option Modal (For Wizard) -->
@include('admin.pages.products.wizard.modals.create-product-option')

<!-- Create Attribute Modal -->
<div class="modal fade" id="createAttributeModal" tabindex="-1" aria-labelledby="createAttributeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAttributeModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Create New Attribute
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createAttributeForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="attr_name" required>
                            <small class="text-muted">e.g., Weight, Dimensions, Material</small>
                        </div>

                        <!-- Handle (auto-generated) -->
                        <div class="col-md-6">
                            <label class="form-label">Handle <span class="text-muted">(auto-generated)</span></label>
                            <input type="text" class="form-control" name="handle" id="attr_handle" readonly>
                            <small class="text-muted">Used for technical reference</small>
                        </div>

                        <!-- Attribute Type -->
                        <div class="col-md-6">
                            <label class="form-label">Attribute Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="attribute_type" required>
                                <option value="product" selected>Product</option>
                                <option value="product_variant">Product Variant</option>
                                <option value="collection">Collection</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>

                        <!-- Field Type -->
                        <div class="col-md-6">
                            <label class="form-label">Field Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="attr_type" required>
                                <option value="text" selected>Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="number">Number</option>
                                <option value="decimal">Decimal</option>
                                <option value="boolean">Yes/No (Boolean)</option>
                                <option value="select">Dropdown (Select)</option>
                                <option value="date">Date</option>
                            </select>
                        </div>

                        <!-- Attribute Group -->
                        <div class="col-md-6">
                            <label class="form-label">Attribute Group</label>
                            <select class="form-select" name="attribute_group_id">
                                <option value="">Select Group (Optional)</option>
                                @if(isset($attributeGroups))
                                    @foreach($attributeGroups as $group)
                                        @php
                                            $groupName = is_array($group->name) ? ($group->name['en'] ?? $group->name[0] ?? '') : $group->name;
                                        @endphp
                                        <option value="{{ $group->id }}">{{ $groupName }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Position -->
                        <div class="col-md-6">
                            <label class="form-label">Position</label>
                            <input type="number" class="form-control" name="position" value="0" min="0">
                            <small class="text-muted">Display order</small>
                        </div>

                        <!-- Options for Select type -->
                        <div class="col-12" id="selectOptionsContainer" style="display: none;">
                            <label class="form-label">Dropdown Options</label>
                            <textarea class="form-control" name="select_options" id="select_options" rows="3" placeholder="Enter options, one per line"></textarea>
                            <small class="text-muted">Enter each option on a new line</small>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Help text for this attribute"></textarea>
                        </div>

                        <!-- Switches -->
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="required" id="attr_required">
                                <label class="form-check-label" for="attr_required">Required Field</label>
                            </div>
                        </div>
                    </div>

                    <div id="attributeFormError" class="alert alert-danger mt-3" style="display: none;"></div>
                    <div id="attributeFormSuccess" class="alert alert-success mt-3" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="createAttributeBtn">
                        <i class="bi bi-check-circle"></i> Create Attribute
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pass data from Laravel to JavaScript
    window.wizardData = {
        productTypes: @json($productTypes),
        brands: @json($brands),
        collections: @json($collections),
        tags: @json($tags),
        customerGroups: @json($customerGroups),
        productOptions: @json($productOptions),
        attributes: @json($attributes ?? []),
        storeUrl: "{{ route('admin.products.wizard.store') }}",
        createAttributeUrl: "{{ route('admin.attributes.store') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('admin/js/wizard/step-manager.js') }}"></script>
<script src="{{ asset('admin/js/wizard/variant-generator.js') }}"></script>
<script src="{{ asset('admin/js/wizard/price-calculator.js') }}"></script>
<script src="{{ asset('admin/js/wizard/form-persistence.js') }}"></script>
<script src="{{ asset('admin/js/product-wizard.js') }}"></script>
<script>
// Attribute Modal Management
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('createAttributeModal');
    const form = document.getElementById('createAttributeForm');
    const nameInput = document.getElementById('attr_name');
    const handleInput = document.getElementById('attr_handle');
    const typeSelect = document.getElementById('attr_type');
    const selectOptionsContainer = document.getElementById('selectOptionsContainer');
    const createBtn = document.getElementById('createAttributeBtn');
    const errorDiv = document.getElementById('attributeFormError');
    const successDiv = document.getElementById('attributeFormSuccess');
    console.log('url',window.wizardData.createAttributeUrl)

    // Auto-generate handle from name
    nameInput?.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '_')
            .replace(/^-+|-+$/g, '');
        handleInput.value = slug;
    });

    // Show/hide options field based on type
    typeSelect?.addEventListener('change', function() {
        if (this.value === 'select') {
            selectOptionsContainer.style.display = 'block';
        } else {
            selectOptionsContainer.style.display = 'none';
        }
    });

    // Handle form submission
    form?.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Disable button
        createBtn.disabled = true;
        createBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

        // Hide previous messages
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';

        // Prepare form data
        const formData = new FormData(form);

        // Debug: Log all form data
        console.log('Form element:', form);
        console.log('FormData entries:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        // Handle select options
        if (typeSelect.value === 'select') {
            const options = document.getElementById('select_options').value
                .split('\n')
                .filter(opt => opt.trim())
                .map(opt => opt.trim());

            if (options.length > 0) {
                formData.set('configuration', JSON.stringify({ options: options }));
            }
        }

        // If no attribute_group_id selected, set a default value
        if (!formData.get('attribute_group_id')) {
            formData.delete('attribute_group_id');
        }

        try {
            console.log('Sending to URL:', window.wizardData.createAttributeUrl);
            const response = await fetch(window.wizardData.createAttributeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.wizardData.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            console.log('Response redirected:', response.redirected);

            const result = await response.json();

            console.log('Erreur',result)


            if (result.success) {
                // Show success message
                successDiv.textContent = result.message || 'Attribute created successfully!';
                successDiv.style.display = 'block';

                // Add new attribute to the page
                addAttributeToForm(result.attribute);

                // Update window.wizardData.attributes
                window.wizardData.attributes.push(result.attribute);

                // Reset form and close modal after a short delay
                setTimeout(() => {
                    form.reset();
                    bootstrap.Modal.getInstance(modal).hide();
                    successDiv.style.display = 'none';
                }, 1500);
            } else {
                throw new Error(result.message || 'Failed to create attribute');
            }
        } catch (error) {
            errorDiv.textContent = error.message || 'An error occurred while creating the attribute';
            errorDiv.style.display = 'block';
        } finally {
            // Re-enable button
            createBtn.disabled = false;
            createBtn.innerHTML = '<i class="bi bi-check-circle"></i> Create Attribute';
        }
    });

    // Reset form when modal is closed
    modal?.addEventListener('hidden.bs.modal', function() {
        form.reset();
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        selectOptionsContainer.style.display = 'none';
    });
});

/**
 * Add newly created attribute to the form
 */
function addAttributeToForm(attribute) {
    const container = document.getElementById('attributesContainer');

    // Check if "no attributes" message exists
    const noAttrsMsg = container.querySelector('p.text-muted');
    if (noAttrsMsg && noAttrsMsg.textContent.includes('No attributes configured')) {
        noAttrsMsg.remove();
    }

    const attrName = attribute.name?.en || attribute.name || 'New Attribute';
    const attrType = attribute.type;
    const isRequired = attribute.required;
    const config = attribute.configuration || {};
    const attrDescription = attribute.description?.en || attribute.description || '';

    let fieldHtml = '';

    // Build field based on type
    if (attrType === 'text' || attrType === 'string') {
        fieldHtml = `<input type="text" class="form-control attribute-input"
            data-attribute-id="${attribute.id}"
            data-attribute-handle="${attribute.handle}"
            placeholder="Enter ${attrName.toLowerCase()}"
            ${isRequired ? 'required' : ''}>`;
    } else if (attrType === 'textarea') {
        fieldHtml = `<textarea class="form-control attribute-input"
            data-attribute-id="${attribute.id}"
            data-attribute-handle="${attribute.handle}"
            rows="2"
            placeholder="Enter ${attrName.toLowerCase()}"
            ${isRequired ? 'required' : ''}></textarea>`;
    } else if (attrType === 'number' || attrType === 'integer' || attrType === 'decimal') {
        fieldHtml = `<input type="number" class="form-control attribute-input"
            data-attribute-id="${attribute.id}"
            data-attribute-handle="${attribute.handle}"
            step="${attrType === 'decimal' ? '0.01' : '1'}"
            placeholder="Enter ${attrName.toLowerCase()}"
            ${isRequired ? 'required' : ''}>`;
    } else if (attrType === 'select' || attrType === 'dropdown') {
        let optionsHtml = '<option value="">Select ' + attrName.toLowerCase() + '</option>';
        if (config.options && Array.isArray(config.options)) {
            config.options.forEach(opt => {
                optionsHtml += `<option value="${opt}">${opt}</option>`;
            });
        }
        fieldHtml = `<select class="form-select attribute-input"
            data-attribute-id="${attribute.id}"
            data-attribute-handle="${attribute.handle}"
            ${isRequired ? 'required' : ''}>${optionsHtml}</select>`;
    } else if (attrType === 'boolean' || attrType === 'checkbox') {
        fieldHtml = `<div class="form-check form-switch">
            <input class="form-check-input attribute-input" type="checkbox"
                data-attribute-id="${attribute.id}"
                data-attribute-handle="${attribute.handle}"
                value="1">
            <label class="form-check-label">Enable ${attrName.toLowerCase()}</label>
        </div>`;
    } else if (attrType === 'date') {
        fieldHtml = `<input type="date" class="form-control attribute-input"
            data-attribute-id="${attribute.id}"
            data-attribute-handle="${attribute.handle}"
            ${isRequired ? 'required' : ''}>`;
    } else {
        fieldHtml = `<input type="text" class="form-control attribute-input"
            data-attribute-id="${attribute.id}"
            data-attribute-handle="${attribute.handle}"
            placeholder="Enter ${attrName.toLowerCase()}"
            ${isRequired ? 'required' : ''}>`;
    }

    const attributeHtml = `
        <div class="mb-3" data-attribute-id="${attribute.id}">
            <label class="form-label">
                ${attrName}
                ${isRequired ? '<span class="text-danger">*</span>' : ''}
            </label>
            ${attrDescription ? `<small class="text-muted d-block mb-1">${attrDescription}</small>` : ''}
            ${fieldHtml}
        </div>
    `;

    container.insertAdjacentHTML('beforeend', attributeHtml);
}
</script>
@endpush
