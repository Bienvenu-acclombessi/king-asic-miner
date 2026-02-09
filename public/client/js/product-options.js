/**
 * Product Options Handler
 * Manages product option selection and variant switching
 */
document.addEventListener('DOMContentLoaded', function() {
    // Store selected options
    let selectedOptions = {};

    // Product data (should be passed from blade template)
    const productData = window.productData || {};
    const variants = productData.variants || [];

    /**
     * Handle button option selection
     */
    document.querySelectorAll('.option-button').forEach(button => {
        button.addEventListener('click', function() {
            const optionId = this.dataset.optionId;
            const valueId = this.dataset.valueId;
            const valueName = this.dataset.valueName;

            // Remove active class from siblings
            const siblings = this.parentElement.querySelectorAll('.option-button');
            siblings.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Store selection
            selectedOptions[optionId] = {
                valueId: valueId,
                valueName: valueName
            };

            // Update variant
            updateVariant();
        });
    });

    /**
     * Handle color option selection
     */
    document.querySelectorAll('.option-color').forEach(button => {
        button.addEventListener('click', function() {
            const optionId = this.dataset.optionId;
            const valueId = this.dataset.valueId;
            const valueName = this.dataset.valueName;

            // Remove active class from siblings
            const siblings = this.parentElement.querySelectorAll('.option-color');
            siblings.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Store selection
            selectedOptions[optionId] = {
                valueId: valueId,
                valueName: valueName
            };

            // Update variant
            updateVariant();
        });
    });

    /**
     * Handle dropdown option selection
     */
    document.querySelectorAll('.option-select').forEach(select => {
        select.addEventListener('change', function() {
            const optionId = this.dataset.optionId;
            const valueId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const valueName = selectedOption?.dataset.valueName || '';

            if (valueId) {
                selectedOptions[optionId] = {
                    valueId: valueId,
                    valueName: valueName
                };
            } else {
                delete selectedOptions[optionId];
            }

            // Update variant
            updateVariant();
        });
    });

    /**
     * Handle radio option selection
     */
    document.querySelectorAll('.option-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const optionId = this.dataset.optionId;
                const valueId = this.value;
                const valueName = this.dataset.valueName;

                selectedOptions[optionId] = {
                    valueId: valueId,
                    valueName: valueName
                };

                // Update variant
                updateVariant();
            }
        });
    });

    /**
     * Handle clear option
     */
    document.querySelectorAll('.option-clear').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const optionId = this.dataset.optionId;

            // Remove from selected options
            delete selectedOptions[optionId];

            // Clear UI
            const optionContainer = this.closest('[data-option-id]');
            if (optionContainer) {
                // Clear buttons
                optionContainer.querySelectorAll('.option-button, .option-color').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Clear select
                const select = optionContainer.querySelector('.option-select');
                if (select) {
                    select.value = '';
                }

                // Clear radio
                const radios = optionContainer.querySelectorAll('.option-radio');
                radios.forEach(radio => radio.checked = false);
            }

            // Update variant
            updateVariant();
        });
    });

    /**
     * Find matching variant based on selected options
     */
    function findMatchingVariant() {
        if (!variants || variants.length === 0) {
            return null;
        }

        // Get array of selected value IDs
        const selectedValueIds = Object.values(selectedOptions).map(opt => opt.valueId);

        if (selectedValueIds.length === 0) {
            // No options selected, return first variant
            return variants[0];
        }

        // Find variant that matches all selected options
        return variants.find(variant => {
            if (!variant.option_values || variant.option_values.length === 0) {
                return selectedValueIds.length === 0;
            }

            // Check if all selected values are in this variant
            return selectedValueIds.every(valueId =>
                variant.option_values.includes(parseInt(valueId))
            );
        });
    }

    /**
     * Update displayed variant information
     */
    function updateVariant() {
        const variant = findMatchingVariant();

        if (!variant) {
            console.log('No matching variant found');
            return;
        }

        console.log('Found variant:', variant);

        // Update SKU
        const skuElement = document.querySelector('[data-sku]');
        if (skuElement && variant.sku) {
            skuElement.textContent = variant.sku;
        }

        // Update Price
        const priceElement = document.querySelector('[data-price]');
        if (priceElement && variant.price) {
            const price = parseFloat(variant.price) / 100;
            priceElement.textContent = '$' + price.toFixed(2);
        }

        // Update Compare Price
        const comparePriceElement = document.querySelector('[data-compare-price]');
        if (comparePriceElement) {
            if (variant.compare_price && variant.compare_price > variant.price) {
                const comparePrice = parseFloat(variant.compare_price) / 100;
                comparePriceElement.textContent = '$' + comparePrice.toFixed(2);
                comparePriceElement.style.display = '';
            } else {
                comparePriceElement.style.display = 'none';
            }
        }

        // Update Stock Status
        const stockElement = document.querySelector('[data-stock]');
        if (stockElement) {
            if (variant.stock > 0) {
                stockElement.textContent = 'In Stock (' + variant.stock + ' available)';
                stockElement.className = 'badge bg-success';
            } else {
                stockElement.textContent = 'Out of Stock';
                stockElement.className = 'badge bg-danger';
            }
        }

        // Update Add to Cart button state
        const addToCartBtn = document.querySelector('[data-add-to-cart]');
        if (addToCartBtn) {
            addToCartBtn.dataset.variantId = variant.id;
            if (variant.stock > 0 && variant.purchasable) {
                addToCartBtn.disabled = false;
            } else {
                addToCartBtn.disabled = true;
            }
        }

        // Trigger custom event
        const event = new CustomEvent('variantChanged', {
            detail: { variant: variant, selectedOptions: selectedOptions }
        });
        document.dispatchEvent(event);
    }

    // Initialize with first variant if no options selected
    if (variants.length > 0 && Object.keys(selectedOptions).length === 0) {
        // Pre-select first option value for each option (if button type)
        document.querySelectorAll('[data-option-id]').forEach(optionContainer => {
            const firstButton = optionContainer.querySelector('.option-button.active');
            if (firstButton) {
                const optionId = firstButton.dataset.optionId;
                const valueId = firstButton.dataset.valueId;
                const valueName = firstButton.dataset.valueName;

                selectedOptions[optionId] = {
                    valueId: valueId,
                    valueName: valueName
                };
            }

            const firstRadio = optionContainer.querySelector('.option-radio:checked');
            if (firstRadio) {
                const optionId = firstRadio.dataset.optionId;
                const valueId = firstRadio.value;
                const valueName = firstRadio.dataset.valueName;

                selectedOptions[optionId] = {
                    valueId: valueId,
                    valueName: valueName
                };
            }
        });

        updateVariant();
    }
});
