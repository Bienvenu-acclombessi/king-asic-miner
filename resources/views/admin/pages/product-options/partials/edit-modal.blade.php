<!-- Edit Product Option Modal -->
<div class="modal fade" id="editProductOptionModal" tabindex="-1" aria-labelledby="editProductOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="editProductOptionForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_product_option_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductOptionModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Product Option
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Option Basic Info -->
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Option Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" placeholder="e.g., Hash Rate, Color, Warranty" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_label" class="form-label">Label</label>
                        <input type="text" name="label" id="edit_label" class="form-control" placeholder="e.g., Choose hash rate">
                        <small class="text-muted">Optional display label for the option.</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_handle" class="form-label">Handle</label>
                        <input type="text" name="handle" id="edit_handle" class="form-control" placeholder="e.g., hash-rate">
                        <small class="text-muted">Unique identifier.</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="shared" id="edit_shared" value="1">
                            <label class="form-check-label" for="edit_shared">
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

                        <div id="edit_values_container" class="mb-3">
                            <!-- Existing values will be loaded here -->
                            <div class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted small mt-2 mb-0">Loading values...</p>
                            </div>
                        </div>

                        <div class="d-flex gap-2 align-items-end">
                            <div class="flex-grow-1">
                                <input type="text" id="edit_new_value_input" class="form-control" placeholder="Enter new value to add">
                            </div>
                            <button type="button" class="btn btn-outline-primary" onclick="addEditValue()">
                                <i class="bi bi-plus-circle me-1"></i>Add Value
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">Press Enter or click "Add Value" to add a new option value</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Product Option
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.edit-value-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s;
}
.edit-value-item:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}
.edit-value-item.existing-value {
    background: #d1ecf1;
    border-color: #bee5eb;
}
.edit-value-item.existing-value:hover {
    background: #b8daff;
    border-color: #7abaff;
}
.edit-value-item-text {
    flex-grow: 1;
    font-weight: 500;
}
.edit-value-item-input {
    flex-grow: 1;
}
.edit-value-item-badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}
</style>

<script>
let editValueIndex = 0;
let existingValuesData = [];

// Add value on Enter key
document.getElementById('edit_new_value_input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addEditValue();
    }
});

function addEditValue() {
    const input = document.getElementById('edit_new_value_input');
    const value = input.value.trim();

    if (!value) {
        alert('Please enter a value');
        return;
    }

    const container = document.getElementById('edit_values_container');
    const noValuesAlert = container.querySelector('.alert-info');

    if (noValuesAlert) {
        noValuesAlert.remove();
    }

    const valueHtml = `
        <div class="edit-value-item" data-index="${editValueIndex}">
            <div class="edit-value-item-text">${escapeHtml(value)}</div>
            <input type="hidden" name="new_values[${editValueIndex}][name]" value="${escapeHtml(value)}">
            <span class="badge bg-success edit-value-item-badge">NEW</span>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEditValue(this)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', valueHtml);
    input.value = '';
    input.focus();
    editValueIndex++;
}

function removeEditValue(btn) {
    const valueItem = btn.closest('.edit-value-item');
    const valueId = valueItem.dataset.valueId;

    if (valueId) {
        // Existing value - mark for deletion
        const deletedInput = document.createElement('input');
        deletedInput.type = 'hidden';
        deletedInput.name = `deleted_values[]`;
        deletedInput.value = valueId;
        document.getElementById('editProductOptionForm').appendChild(deletedInput);
    }

    valueItem.remove();

    const container = document.getElementById('edit_values_container');
    if (container.children.length === 0) {
        container.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>No values remaining. At least one value is required.
            </div>
        `;
    }
}

function toggleEditValue(btn) {
    const valueItem = btn.closest('.edit-value-item');
    const textDiv = valueItem.querySelector('.edit-value-item-text');
    const input = valueItem.querySelector('input[type="text"]');

    if (input) {
        // Currently editing - save
        const newValue = input.value.trim();
        if (newValue) {
            textDiv.textContent = newValue;
            textDiv.style.display = 'block';
            input.remove();
            btn.innerHTML = '<i class="bi bi-pencil"></i>';
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-outline-secondary');

            // Update hidden input
            const hiddenInput = valueItem.querySelector('input[type="hidden"]');
            hiddenInput.value = newValue;
        }
    } else {
        // Start editing
        const currentValue = textDiv.textContent;
        textDiv.style.display = 'none';

        const editInput = document.createElement('input');
        editInput.type = 'text';
        editInput.className = 'form-control form-control-sm edit-value-item-input';
        editInput.value = currentValue;
        textDiv.insertAdjacentElement('afterend', editInput);
        editInput.focus();

        btn.innerHTML = '<i class="bi bi-check"></i>';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-outline-success');
    }
}

function loadExistingValues(values) {
    const container = document.getElementById('edit_values_container');
    container.innerHTML = '';

    if (!values || values.length === 0) {
        container.innerHTML = `
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>No values found. Add values using the form below.
            </div>
        `;
        return;
    }

    existingValuesData = values;

    values.forEach(value => {
        const valueName = (typeof value.name === 'object')
            ? (value.name.en || value.name.fr || Object.values(value.name)[0] || 'N/A')
            : (value.name || 'N/A');

        const valueHtml = `
            <div class="edit-value-item existing-value" data-value-id="${value.id}">
                <div class="edit-value-item-text">${escapeHtml(valueName)}</div>
                <input type="hidden" name="existing_values[${value.id}][name]" value="${escapeHtml(valueName)}">
                <input type="hidden" name="existing_values[${value.id}][id]" value="${value.id}">
                <span class="badge bg-info edit-value-item-badge">EXISTING</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleEditValue(this)" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEditValue(this)" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', valueHtml);
    });
}

// Validate form before submit
document.getElementById('editProductOptionForm')?.addEventListener('submit', function(e) {
    const container = document.getElementById('edit_values_container');
    const hasValues = container.querySelector('.edit-value-item') !== null;

    if (!hasValues) {
        e.preventDefault();
        alert('Please add at least one value for this option');
        return false;
    }
});
</script>
