/**
 * Product Variants Manager
 * Manages product variations with multiple attributes
 */

class VariantsManager {
    constructor() {
        this.variantCounter = 0;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadExistingVariants();
    }

    bindEvents() {
        const enableVariantsCheckbox = document.getElementById('enableVariants');
        const variantsBody = document.getElementById('variantsBody');
        const generateBtn = document.getElementById('generateVariants');

        // Toggle variants section
        if (enableVariantsCheckbox) {
            enableVariantsCheckbox.addEventListener('change', function() {
                variantsBody.style.display = this.checked ? 'block' : 'none';
            });
        }

        // Attribute checkbox toggle
        document.querySelectorAll('.attribute-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const attributeCard = this.closest('.attribute-card');
                const valuesDiv = attributeCard.querySelector('.attribute-values');

                if (this.checked) {
                    valuesDiv.style.display = 'block';
                    attributeCard.classList.add('border-primary');
                } else {
                    valuesDiv.style.display = 'none';
                    attributeCard.classList.remove('border-primary');
                    // Uncheck all values
                    valuesDiv.querySelectorAll('.value-checkbox').forEach(v => v.checked = false);
                }
            });
        });

        // Generate variations button
        if (generateBtn) {
            generateBtn.addEventListener('click', () => this.generateVariations());
        }

        // Select all variants
        const selectAll = document.getElementById('selectAllVariants');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.variant-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        }

        // Delete selected variants
        const deleteBtn = document.getElementById('deleteSelectedVariants');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => this.deleteSelectedVariants());
        }

        // Apply bulk price
        const bulkPriceBtn = document.getElementById('applyBulkPrice');
        if (bulkPriceBtn) {
            bulkPriceBtn.addEventListener('click', () => this.applyBulkPrice());
        }

        // Apply bulk stock
        const bulkStockBtn = document.getElementById('applyBulkStock');
        if (bulkStockBtn) {
            bulkStockBtn.addEventListener('click', () => this.applyBulkStock());
        }
    }

    generateVariations() {
        // Get selected attributes and their values
        const selectedAttributes = [];

        document.querySelectorAll('.attribute-checkbox:checked').forEach(attrCheckbox => {
            const attributeId = attrCheckbox.value;
            const attributeName = attrCheckbox.closest('.card-body').querySelector('label').textContent.trim();
            const values = [];

            document.querySelectorAll(`.value-checkbox[data-attribute-id="${attributeId}"]:checked`).forEach(valCheckbox => {
                values.push({
                    id: valCheckbox.value,
                    text: valCheckbox.dataset.valueText
                });
            });

            if (values.length > 0) {
                selectedAttributes.push({
                    id: attributeId,
                    name: attributeName,
                    values: values
                });
            }
        });

        if (selectedAttributes.length === 0) {
            alert('Please select at least one attribute with values');
            return;
        }

        // Generate all combinations
        const combinations = this.generateCombinations(selectedAttributes);

        // Display combinations in table
        this.displayVariations(combinations);
    }

    generateCombinations(attributes) {
        if (attributes.length === 0) return [];

        const result = [];

        function combine(current, index) {
            if (index === attributes.length) {
                result.push([...current]);
                return;
            }

            for (const value of attributes[index].values) {
                current.push({
                    attributeId: attributes[index].id,
                    attributeName: attributes[index].name,
                    valueId: value.id,
                    valueText: value.text
                });
                combine(current, index + 1);
                current.pop();
            }
        }

        combine([], 0);
        return result;
    }

    displayVariations(combinations) {
        const tbody = document.getElementById('variantsTableBody');
        const tableContainer = document.getElementById('variantsTableContainer');
        const noVariantsMessage = document.getElementById('noVariantsMessage');

        // Clear existing generated variants (keep manually added ones if any)
        tbody.innerHTML = '';
        this.variantCounter = 0;

        combinations.forEach((combination) => {
            const index = this.variantCounter++;
            const variantName = combination.map(c => c.valueText).join(' + ');
            const valueIds = combination.map(c => c.valueId).join(',');

            const row = `
                <tr class="variant-row" data-variant-index="${index}">
                    <td>
                        <input type="checkbox" class="variant-checkbox">
                    </td>
                    <td>
                        <input type="hidden" name="variants[${index}][name]" value="${variantName}">
                        <input type="hidden" name="variants[${index}][attribute_value_ids]" value="${valueIds}">
                        <strong>${variantName}</strong>
                    </td>
                    <td>
                        <input type="text" name="variants[${index}][sku]" class="form-control form-control-sm"
                               placeholder="SKU-${index + 1}">
                    </td>
                    <td>
                        <input type="number" step="0.01" name="variants[${index}][additional_price]"
                               class="form-control form-control-sm variant-price" value="0" placeholder="0.00">
                    </td>
                    <td>
                        <input type="number" name="variants[${index}][quantity]"
                               class="form-control form-control-sm variant-stock" value="0" placeholder="0">
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="variants[${index}][is_active]" value="1"
                                   class="form-check-input" checked>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-variant" onclick="variantsManager.deleteVariant(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            tbody.insertAdjacentHTML('beforeend', row);
        });

        tableContainer.style.display = 'block';
        noVariantsMessage.style.display = 'none';
    }

    deleteVariant(index) {
        if (confirm('Delete this variation?')) {
            const row = document.querySelector(`[data-variant-index="${index}"]`);
            if (row) {
                row.remove();
            }
        }
    }

    deleteSelectedVariants() {
        const selected = document.querySelectorAll('.variant-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select variants to delete');
            return;
        }

        if (confirm(`Delete ${selected.length} selected variation(s)?`)) {
            selected.forEach(checkbox => {
                checkbox.closest('tr').remove();
            });
        }
    }

    applyBulkPrice() {
        const price = prompt('Enter price adjustment for all variants:');
        if (price !== null) {
            document.querySelectorAll('.variant-price').forEach(input => {
                input.value = price;
            });
        }
    }

    applyBulkStock() {
        const stock = prompt('Enter stock quantity for all variants:');
        if (stock !== null) {
            document.querySelectorAll('.variant-stock').forEach(input => {
                input.value = stock;
            });
        }
    }

    loadExistingVariants() {
        // This method will be populated with existing variants from the server on edit page
        // For now, check if table has existing rows
        const tbody = document.getElementById('variantsTableBody');
        if (tbody && tbody.children.length > 0) {
            const tableContainer = document.getElementById('variantsTableContainer');
            const noVariantsMessage = document.getElementById('noVariantsMessage');
            if (tableContainer) tableContainer.style.display = 'block';
            if (noVariantsMessage) noVariantsMessage.style.display = 'none';
            this.variantCounter = tbody.children.length;
        }
    }
}

// Initialize when DOM is ready
let variantsManager;
document.addEventListener('DOMContentLoaded', function() {
    variantsManager = new VariantsManager();
});
