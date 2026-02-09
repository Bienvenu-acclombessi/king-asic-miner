<!-- Create Product Option Modal -->
<div class="modal fade" id="createProductOptionModal" tabindex="-1" aria-labelledby="createProductOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.product-options.store') }}" method="POST" id="createProductOptionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductOptionModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Product Option
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Option Basic Info -->
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Option Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="e.g., Hash Rate, Color, Warranty" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_label" class="form-label">Label</label>
                        <input type="text" name="label" id="create_label" class="form-control" placeholder="e.g., Choose hash rate">
                        <small class="text-muted">Optional display label for the option.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_handle" class="form-label">Handle</label>
                        <input type="text" name="handle" id="create_handle" class="form-control" placeholder="e.g., hash-rate">
                        <small class="text-muted">Unique identifier (leave empty to auto-generate from name).</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="shared" id="create_shared" value="1" checked>
                            <label class="form-check-label" for="create_shared">
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

                        <div id="create_values_container" class="mb-3">
                            <!-- Values will be added here dynamically -->
                            <div class="alert alert-info" id="create_no_values_alert">
                                <i class="bi bi-info-circle me-2"></i>No values added yet. Click "Add Value" below to add option values.
                            </div>
                        </div>

                        <div class="d-flex gap-2 align-items-end">
                            <div class="flex-grow-1">
                                <input type="text" id="create_new_value_input" class="form-control" placeholder="Enter value (e.g., M72S 282T Mixed--Feb Batch)">
                            </div>
                            <button type="button" class="btn btn-outline-primary" onclick="addCreateValue()">
                                <i class="bi bi-plus-circle me-1"></i>Add Value
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">Press Enter or click "Add Value" to add a new option value</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
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
let createValueIndex = 0;

// Auto-generate handle from name
document.getElementById('create_name')?.addEventListener('input', function() {
    const handleInput = document.getElementById('create_handle');
    if (!handleInput.dataset.manuallyEdited) {
        handleInput.value = this.value
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
});

document.getElementById('create_handle')?.addEventListener('input', function() {
    this.dataset.manuallyEdited = 'true';
});

// Add value on Enter key
document.getElementById('create_new_value_input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addCreateValue();
    }
});

function addCreateValue() {
    const input = document.getElementById('create_new_value_input');
    const value = input.value.trim();

    if (!value) {
        alert('Please enter a value');
        return;
    }

    const container = document.getElementById('create_values_container');
    const noValuesAlert = document.getElementById('create_no_values_alert');

    if (noValuesAlert) {
        noValuesAlert.remove();
    }

    const valueHtml = `
        <div class="value-item" data-index="${createValueIndex}">
            <div class="value-item-text">${escapeHtml(value)}</div>
            <input type="hidden" name="values[${createValueIndex}][name]" value="${escapeHtml(value)}">
            <div class="value-item-actions">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCreateValue(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', valueHtml);
    input.value = '';
    input.focus();
    createValueIndex++;
}

function removeCreateValue(btn) {
    const valueItem = btn.closest('.value-item');
    valueItem.remove();

    const container = document.getElementById('create_values_container');
    if (container.children.length === 0) {
        container.innerHTML = `
            <div class="alert alert-info" id="create_no_values_alert">
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
    document.getElementById('createProductOptionForm').reset();
    document.getElementById('create_handle').dataset.manuallyEdited = '';
    createValueIndex = 0;

    const container = document.getElementById('create_values_container');
    container.innerHTML = `
        <div class="alert alert-info" id="create_no_values_alert">
            <i class="bi bi-info-circle me-2"></i>No values added yet. Click "Add Value" below to add option values.
        </div>
    `;
});

// Validate form before submit
document.getElementById('createProductOptionForm')?.addEventListener('submit', function(e) {
    const container = document.getElementById('create_values_container');
    const hasValues = container.querySelector('.value-item') !== null;

    if (!hasValues) {
        e.preventDefault();
        alert('Please add at least one value for this option');
        return false;
    }
});
</script>
