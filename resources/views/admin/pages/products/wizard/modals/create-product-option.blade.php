<!-- Create Product Option Modal (For Wizard) -->
<style>
/* Fix modal z-index to appear above sidebar */
#createProductOptionModal.modal.show {
    z-index: 9999 !important;
}
#createProductOptionModal ~ .modal-backdrop.show {
    z-index: 9998 !important;
}
</style>
<div class="modal fade" id="createProductOptionModal" tabindex="-1" aria-labelledby="createProductOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="createProductOptionFormWizard">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductOptionModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Create New Product Option
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Option Basic Info -->
                    <div class="mb-3">
                        <label for="wizard_option_name" class="form-label">Option Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="wizard_option_name" class="form-control" placeholder="e.g., Hash Rate, Color, Warranty" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <label for="wizard_option_label" class="form-label">Label</label>
                        <input type="text" name="label" id="wizard_option_label" class="form-control" placeholder="e.g., Choose hash rate">
                        <small class="text-muted">Optional display label for the option.</small>
                    </div>

                    <div class="mb-3">
                        <label for="wizard_option_handle" class="form-label">Handle</label>
                        <input type="text" name="handle" id="wizard_option_handle" class="form-control" placeholder="e.g., hash-rate">
                        <small class="text-muted">Unique identifier (leave empty to auto-generate from name).</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="shared" id="wizard_option_shared" value="1" checked>
                            <label class="form-check-label" for="wizard_option_shared">
                                Shared Option
                            </label>
                        </div>
                        <small class="text-muted">Shared options can be used across multiple products.</small>
                    </div>

                    <hr class="my-4">

                    <!-- Option Values Section -->
                    <div class="mb-3">
                        <label class="form-label">
                            Option Values <span class="text-danger">*</span>
                            <small class="text-muted ms-2">(At least one value required)</small>
                        </label>

                        <div id="wizard_values_container" class="mb-3">
                            <!-- Values will be added here dynamically -->
                            <div class="alert alert-info" id="wizard_no_values_alert">
                                <i class="bi bi-info-circle me-2"></i>No values added yet. Click "Add Value" below to add option values.
                            </div>
                        </div>

                        <div class="d-flex gap-2 align-items-end">
                            <div class="flex-grow-1">
                                <input type="text" id="wizard_new_value_input" class="form-control" placeholder="Enter value (e.g., 110 TH/s, 120 TH/s)">
                            </div>
                            <button type="button" class="btn btn-outline-primary" onclick="addWizardValue()">
                                <i class="bi bi-plus-circle me-1"></i>Add Value
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">Press Enter or click "Add Value" to add a new option value</small>
                    </div>

                    <div id="wizard_option_error" class="alert alert-danger" style="display: none;"></div>
                    <div id="wizard_option_success" class="alert alert-success" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="wizard_create_option_btn">
                        <i class="bi bi-check-circle me-1"></i>Create Product Option
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.value-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s;
}
.value-item:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}
.value-item-text {
    flex-grow: 1;
    font-weight: 500;
}
.value-item-actions {
    display: flex;
    gap: 0.5rem;
}
</style>

<script>
let wizardValueIndex = 0;
let wizardCreatedOption = null;

// Auto-generate handle from name
document.getElementById('wizard_option_name')?.addEventListener('input', function() {
    const handleInput = document.getElementById('wizard_option_handle');
    if (!handleInput.dataset.manuallyEdited) {
        handleInput.value = this.value
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
});

document.getElementById('wizard_option_handle')?.addEventListener('input', function() {
    this.dataset.manuallyEdited = 'true';
});

// Add value on Enter key
document.getElementById('wizard_new_value_input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addWizardValue();
    }
});

function addWizardValue() {
    const input = document.getElementById('wizard_new_value_input');
    const value = input.value.trim();

    if (!value) {
        alert('Please enter a value');
        return;
    }

    const container = document.getElementById('wizard_values_container');
    const noValuesAlert = document.getElementById('wizard_no_values_alert');

    if (noValuesAlert) {
        noValuesAlert.remove();
    }

    const valueHtml = `
        <div class="value-item" data-index="${wizardValueIndex}">
            <div class="value-item-text">${escapeHtml(value)}</div>
            <input type="hidden" name="values[${wizardValueIndex}][name]" value="${escapeHtml(value)}">
            <div class="value-item-actions">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeWizardValue(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', valueHtml);
    input.value = '';
    input.focus();
    wizardValueIndex++;
}

function removeWizardValue(btn) {
    const valueItem = btn.closest('.value-item');
    valueItem.remove();

    const container = document.getElementById('wizard_values_container');
    if (container.children.length === 0) {
        container.innerHTML = `
            <div class="alert alert-info" id="wizard_no_values_alert">
                <i class="bi bi-info-circle me-2"></i>No values added yet. Click "Add Value" below to add option values.
            </div>
        `;
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Reset form when modal is closed
document.getElementById('createProductOptionModal')?.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('createProductOptionFormWizard');
    form.reset();
    document.getElementById('wizard_option_handle').dataset.manuallyEdited = '';
    wizardValueIndex = 0;

    const container = document.getElementById('wizard_values_container');
    container.innerHTML = `
        <div class="alert alert-info" id="wizard_no_values_alert">
            <i class="bi bi-info-circle me-2"></i>No values added yet. Click "Add Value" below to add option values.
        </div>
    `;

    // Hide messages
    document.getElementById('wizard_option_error').style.display = 'none';
    document.getElementById('wizard_option_success').style.display = 'none';
});

// Handle form submission via AJAX
document.getElementById('createProductOptionFormWizard')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const container = document.getElementById('wizard_values_container');
    const hasValues = container.querySelector('.value-item') !== null;

    if (!hasValues) {
        alert('Please add at least one value for this option');
        return false;
    }

    const submitBtn = document.getElementById('wizard_create_option_btn');
    const errorDiv = document.getElementById('wizard_option_error');
    const successDiv = document.getElementById('wizard_option_success');

    // Hide messages
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';

    // Disable button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

    // Prepare form data
    const formData = new FormData(this);

    try {
        const response = await fetch('{{ route("admin.product-options.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.wizardData.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Show success
            successDiv.textContent = result.message;
            successDiv.style.display = 'block';

            // Store the created option
            wizardCreatedOption = result.productOption;

            // Add to global product options list
            if (!window.wizardData.productOptions) {
                window.wizardData.productOptions = [];
            }
            window.wizardData.productOptions.push(result.productOption);

            // Add option to the product automatically
            if (typeof wizard !== 'undefined' && wizard.addOption) {
                wizard.addOption(result.productOption.id);
            }

            // Close modal after short delay
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('createProductOptionModal')).hide();
            }, 1000);

        } else {
            throw new Error(result.message || 'Failed to create product option');
        }
    } catch (error) {
        errorDiv.textContent = error.message || 'An error occurred while creating the product option';
        errorDiv.style.display = 'block';
    } finally {
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Create Product Option';
    }
});
</script>
