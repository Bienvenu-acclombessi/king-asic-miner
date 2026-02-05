/**
 * Product Manager
 * Handles dynamic product creation including variants, attributes, and pricing
 */
class ProductManager {
    constructor(config) {
        this.customerGroups = config.customerGroups || [];
        this.productOptions = config.productOptions || [];
        this.attributes = config.attributes || [];

        this.selectedOptions = [];
        this.variants = [];
        this.customAttributes = [];

        this.init();
    }

    init() {
        this.initializeEventListeners();
        this.updateVariantsVisibility();
    }

    initializeEventListeners() {
        // Product options checkboxes
        document.querySelectorAll('.product-option-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.handleOptionChange(e);
            });
        });

        // Generate variants button
        const generateBtn = document.getElementById('generateVariantsBtn');
        if (generateBtn) {
            generateBtn.addEventListener('click', () => {
                this.generateVariants();
            });
        }

        // Add attribute button
        const addAttrBtn = document.getElementById('addAttributeBtn');
        if (addAttrBtn) {
            addAttrBtn.addEventListener('click', () => {
                this.addAttributeRow();
            });
        }
    }

    handleOptionChange(event) {
        const checkbox = event.target;
        const optionId = parseInt(checkbox.value);
        const optionName = checkbox.dataset.optionName;
        const optionValues = JSON.parse(checkbox.dataset.optionValues);

        if (checkbox.checked) {
            this.selectedOptions.push({
                id: optionId,
                name: optionName,
                values: optionValues
            });
        } else {
            this.selectedOptions = this.selectedOptions.filter(opt => opt.id !== optionId);
        }

        this.updateVariantsVisibility();
    }

    updateVariantsVisibility() {
        const noOptionsMsg = document.getElementById('noOptionsMessage');
        const variantsContainer = document.getElementById('variantsContainer');

        if (this.selectedOptions.length > 0) {
            noOptionsMsg.style.display = 'none';
            variantsContainer.style.display = 'block';
        } else {
            noOptionsMsg.style.display = 'block';
            variantsContainer.style.display = 'none';
            this.variants = [];
        }
    }

    generateVariants() {
        if (this.selectedOptions.length === 0) {
            alert('Please select at least one product option first.');
            return;
        }

        // Generate all combinations
        const combinations = this.generateCombinations(this.selectedOptions);

        this.variants = combinations.map((combo, index) => {
            const existingVariant = this.variants[index];
            return {
                id: existingVariant?.id || this.generateId(),
                option_values: combo.map(v => v.id),
                option_names: combo.map(v => this.getOptionValueName(v.name)),
                sku: existingVariant?.sku || '',
                stock: existingVariant?.stock || 0,
                purchasable: existingVariant?.purchasable || 'always',
                shippable: existingVariant?.shippable !== undefined ? existingVariant.shippable : true,
                weight_value: existingVariant?.weight_value || '',
                weight_unit: existingVariant?.weight_unit || 'kg',
                length_value: existingVariant?.length_value || '',
                width_value: existingVariant?.width_value || '',
                height_value: existingVariant?.height_value || '',
                dimension_unit: existingVariant?.dimension_unit || 'cm',
                prices: existingVariant?.prices || this.getDefaultPrices()
            };
        });

        this.renderVariants();
    }

    generateCombinations(options) {
        if (options.length === 0) return [[]];
        if (options.length === 1) {
            return options[0].values.map(v => [v]);
        }

        const result = [];
        const firstOption = options[0];
        const remainingOptions = options.slice(1);
        const remainingCombinations = this.generateCombinations(remainingOptions);

        firstOption.values.forEach(value => {
            remainingCombinations.forEach(combo => {
                result.push([value, ...combo]);
            });
        });

        return result;
    }

    getOptionValueName(name) {
        if (typeof name === 'object' && name !== null) {
            return name.en || name[Object.keys(name)[0]] || 'N/A';
        }
        return name || 'N/A';
    }

    getDefaultPrices() {
        return this.customerGroups.map(group => ({
            customer_group_id: group.id,
            customer_group_name: group.name,
            price: 0,
            compare_price: null,
            min_quantity: 1
        }));
    }

    renderVariants() {
        const container = document.getElementById('variantsList');

        if (this.variants.length === 0) {
            container.innerHTML = '<p class="text-muted">No variants generated yet.</p>';
            return;
        }

        let html = '<div class="accordion" id="variantsAccordion">';

        this.variants.forEach((variant, index) => {
            const variantName = variant.option_names.join(' / ');
            const collapseId = `collapse-${variant.id}`;

            html += `
                <div class="accordion-item variant-card">
                    <h2 class="accordion-header" id="heading-${variant.id}">
                        <button class="accordion-button ${index !== 0 ? 'collapsed' : ''}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                            <strong>${variantName}</strong>
                            ${variant.sku ? `<span class="badge bg-secondary ms-2">${variant.sku}</span>` : ''}
                        </button>
                    </h2>
                    <div id="${collapseId}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}"
                         data-bs-parent="#variantsAccordion">
                        <div class="accordion-body">
                            ${this.renderVariantFields(variant, index)}
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        container.innerHTML = html;

        // Attach event listeners for variant inputs
        this.attachVariantEventListeners();
    }

    renderVariantFields(variant, index) {
        return `
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control variant-sku" data-index="${index}"
                           value="${variant.sku}" placeholder="Enter SKU">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control variant-stock" data-index="${index}"
                           value="${variant.stock}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select variant-purchasable" data-index="${index}">
                        <option value="always" ${variant.purchasable === 'always' ? 'selected' : ''}>Always</option>
                        <option value="in_stock" ${variant.purchasable === 'in_stock' ? 'selected' : ''}>In Stock</option>
                        <option value="never" ${variant.purchasable === 'never' ? 'selected' : ''}>Never</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input variant-shippable" type="checkbox"
                               data-index="${index}" ${variant.shippable ? 'checked' : ''}>
                        <label class="form-check-label">
                            Shippable Product
                        </label>
                    </div>
                </div>
            </div>

            <h6 class="mb-3">Dimensions & Weight</h6>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Length (cm)</label>
                    <input type="number" step="0.01" class="form-control variant-length"
                           data-index="${index}" value="${variant.length_value}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Width (cm)</label>
                    <input type="number" step="0.01" class="form-control variant-width"
                           data-index="${index}" value="${variant.width_value}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Height (cm)</label>
                    <input type="number" step="0.01" class="form-control variant-height"
                           data-index="${index}" value="${variant.height_value}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" step="0.01" class="form-control variant-weight"
                           data-index="${index}" value="${variant.weight_value}">
                </div>
            </div>

            <h6 class="mb-3">Pricing by Customer Group</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Customer Group</th>
                            <th>Price</th>
                            <th>Compare Price</th>
                            <th>Min Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${this.renderPricingRows(variant, index)}
                    </tbody>
                </table>
            </div>
        `;
    }

    renderPricingRows(variant, variantIndex) {
        return variant.prices.map((price, priceIndex) => `
            <tr>
                <td>${price.customer_group_name}</td>
                <td>
                    <input type="number" step="0.01" class="form-control form-control-sm variant-price"
                           data-variant-index="${variantIndex}" data-price-index="${priceIndex}"
                           value="${price.price}">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control form-control-sm variant-compare-price"
                           data-variant-index="${variantIndex}" data-price-index="${priceIndex}"
                           value="${price.compare_price || ''}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm variant-min-qty"
                           data-variant-index="${variantIndex}" data-price-index="${priceIndex}"
                           value="${price.min_quantity}" min="1">
                </td>
            </tr>
        `).join('');
    }

    attachVariantEventListeners() {
        // SKU inputs
        document.querySelectorAll('.variant-sku').forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.variants[index].sku = e.target.value;
            });
        });

        // Stock inputs
        document.querySelectorAll('.variant-stock').forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.variants[index].stock = parseInt(e.target.value) || 0;
            });
        });

        // Purchasable select
        document.querySelectorAll('.variant-purchasable').forEach(select => {
            select.addEventListener('change', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.variants[index].purchasable = e.target.value;
            });
        });

        // Shippable checkbox
        document.querySelectorAll('.variant-shippable').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.variants[index].shippable = e.target.checked;
            });
        });

        // Dimension inputs
        document.querySelectorAll('.variant-length').forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.variants[index].length_value = parseFloat(e.target.value) || null;
            });
        });

        document.querySelectorAll('.variant-width').forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.variants[index].width_value = parseFloat(e.target.value) || null;
            });
        });

        document.querySelectorAll('.variant-height').forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.variants[index].height_value = parseFloat(e.target.value) || null;
            });
        });

        document.querySelectorAll('.variant-weight').forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.dataset.index);
                this.variants[index].weight_value = parseFloat(e.target.value) || null;
            });
        });

        // Price inputs
        document.querySelectorAll('.variant-price').forEach(input => {
            input.addEventListener('input', (e) => {
                const variantIndex = parseInt(e.target.dataset.variantIndex);
                const priceIndex = parseInt(e.target.dataset.priceIndex);
                this.variants[variantIndex].prices[priceIndex].price = parseFloat(e.target.value) || 0;
            });
        });

        document.querySelectorAll('.variant-compare-price').forEach(input => {
            input.addEventListener('input', (e) => {
                const variantIndex = parseInt(e.target.dataset.variantIndex);
                const priceIndex = parseInt(e.target.dataset.priceIndex);
                this.variants[variantIndex].prices[priceIndex].compare_price = parseFloat(e.target.value) || null;
            });
        });

        document.querySelectorAll('.variant-min-qty').forEach(input => {
            input.addEventListener('input', (e) => {
                const variantIndex = parseInt(e.target.dataset.variantIndex);
                const priceIndex = parseInt(e.target.dataset.priceIndex);
                this.variants[variantIndex].prices[priceIndex].min_quantity = parseInt(e.target.value) || 1;
            });
        });
    }

    // Attributes Management
    addAttributeRow() {
        const id = this.generateId();
        const container = document.getElementById('attributesContainer');

        const attributeHtml = `
            <div class="attribute-item" data-id="${id}">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Attribute Name</label>
                        <input type="text" class="form-control attr-name" data-id="${id}"
                               placeholder="e.g., Material">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Value</label>
                        <input type="text" class="form-control attr-value" data-id="${id}"
                               placeholder="e.g., Cotton">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Type</label>
                        <select class="form-select attr-type" data-id="${id}">
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="boolean">Boolean</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                    <div class="col-md-1 mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-danger remove-attribute" data-id="${id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', attributeHtml);

        // Add event listener for remove button
        document.querySelector(`.remove-attribute[data-id="${id}"]`).addEventListener('click', () => {
            this.removeAttribute(id);
        });

        // Add event listeners for inputs
        this.attachAttributeEventListeners(id);
    }

    attachAttributeEventListeners(id) {
        const nameInput = document.querySelector(`.attr-name[data-id="${id}"]`);
        const valueInput = document.querySelector(`.attr-value[data-id="${id}"]`);
        const typeSelect = document.querySelector(`.attr-type[data-id="${id}"]`);

        const updateAttribute = () => {
            const existing = this.customAttributes.find(attr => attr.id === id);
            const attrData = {
                id: id,
                name: nameInput.value,
                value: valueInput.value,
                type: typeSelect.value
            };

            if (existing) {
                Object.assign(existing, attrData);
            } else {
                this.customAttributes.push(attrData);
            }
        };

        nameInput.addEventListener('input', updateAttribute);
        valueInput.addEventListener('input', updateAttribute);
        typeSelect.addEventListener('change', updateAttribute);
    }

    removeAttribute(id) {
        const element = document.querySelector(`.attribute-item[data-id="${id}"]`);
        if (element) {
            element.remove();
        }
        this.customAttributes = this.customAttributes.filter(attr => attr.id !== id);
    }

    // Update form data before submission
    updateFormData() {
        // Update variants JSON
        const variantsJson = document.getElementById('variantsJson');
        if (variantsJson) {
            const variantsData = this.variants.map(variant => ({
                option_values: variant.option_values,
                sku: variant.sku,
                stock: variant.stock,
                purchasable: variant.purchasable,
                shippable: variant.shippable,
                weight_value: variant.weight_value,
                weight_unit: variant.weight_unit,
                length_value: variant.length_value,
                width_value: variant.width_value,
                height_value: variant.height_value,
                length_unit: 'cm',
                width_unit: 'cm',
                height_unit: 'cm',
                prices: variant.prices.map(price => ({
                    customer_group_id: price.customer_group_id,
                    price: price.price,
                    compare_price: price.compare_price,
                    min_quantity: price.min_quantity
                }))
            }));
            variantsJson.value = JSON.stringify(variantsData);
        }

        // Update attributes JSON
        const attributesJson = document.getElementById('attributesJson');
        if (attributesJson) {
            const attributesData = this.customAttributes.reduce((acc, attr) => {
                if (attr.name && attr.value) {
                    acc[attr.name] = {
                        value: attr.value,
                        type: attr.type
                    };
                }
                return acc;
            }, {});
            attributesJson.value = JSON.stringify(attributesData);
        }
    }

    // Utility: Generate unique ID
    generateId() {
        return 'id_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
}
