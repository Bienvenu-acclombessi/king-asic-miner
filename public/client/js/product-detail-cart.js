/**
 * Product Detail Page - Cart Management
 * Handles product options, variants, and adding to cart from detail page
 */

console.log('📦 Product Detail Cart: File loaded');

class ProductDetailCart {
    constructor(productId) {
        this.productId = productId;
        this.selectedOptions = {};
        this.selectedVariantId = null;
        this.quantity = 1;
        this.productData = window.productData || null;

        console.log('📦 Product Detail Cart: Initializing with product:', this.productId);
        console.log('📦 Product Detail Cart: Product data:', this.productData);

        this.init();
    }

    /**
     * Initialize product detail cart
     */
    init() {
        this.setupEventListeners();
        this.initializeDefaultOptions();
        this.updateAddToCartButton();
    }

    /**
     * Initialize default options (first option selected by default)
     */
    initializeDefaultOptions() {
        console.log('📦 Product Detail Cart: Initializing default options...');

        // For buttons (already have .active class from backend)
        document.querySelectorAll('.option-button.active, .option-color.active').forEach(button => {
            const optionId = button.dataset.optionId;
            const valueId = button.dataset.valueId;
            this.selectedOptions[optionId] = { value_id: valueId };
            console.log('📦 Product Detail Cart: Default button option selected:', { optionId, valueId });
        });

        // For radio inputs (already checked from backend)
        document.querySelectorAll('.option-radio:checked').forEach(radio => {
            const optionId = radio.dataset.optionId;
            const valueId = radio.value;
            this.selectedOptions[optionId] = { value_id: valueId };
            console.log('📦 Product Detail Cart: Default radio option selected:', { optionId, valueId });
        });

        // Check for matching variant
        this.checkVariant();
    }

    /**
     * Setup event listeners for product options
     */
    setupEventListeners() {
        console.log('📦 Product Detail Cart: Setting up event listeners...');

        // Button options (option-button, option-color)
        document.querySelectorAll('.option-button, .option-color').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const optionId = button.dataset.optionId;
                const valueId = button.dataset.valueId;

                console.log('📦 Product Detail Cart: Button option clicked:', { optionId, valueId });

                // Remove active class from siblings
                button.parentElement.querySelectorAll('.option-button, .option-color').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Add active class to clicked button
                button.classList.add('active');

                // Update selected options
                this.selectedOptions[optionId] = { value_id: valueId };

                this.updateAddToCartButton();
                this.checkVariant();
            });
        });

        // Select dropdowns
        document.querySelectorAll('.option-select').forEach(select => {
            select.addEventListener('change', (e) => {
                const optionId = e.target.dataset.optionId;
                const valueId = e.target.value;

                console.log('📦 Product Detail Cart: Select option changed:', { optionId, valueId });

                if (valueId) {
                    this.selectedOptions[optionId] = { value_id: valueId };
                } else {
                    delete this.selectedOptions[optionId];
                }

                this.updateAddToCartButton();
                this.checkVariant();
            });
        });

        // Radio inputs
        document.querySelectorAll('.option-radio').forEach(radio => {
            radio.addEventListener('change', (e) => {
                const optionId = e.target.dataset.optionId;
                const valueId = e.target.value;

                console.log('📦 Product Detail Cart: Radio option changed:', { optionId, valueId });

                this.selectedOptions[optionId] = { value_id: valueId };

                this.updateAddToCartButton();
                this.checkVariant();
            });
        });

        // Custom text inputs
        document.querySelectorAll('.product-option-text').forEach(input => {
            input.addEventListener('input', (e) => {
                const optionId = e.target.dataset.optionId;
                this.selectedOptions[optionId] = { custom_value: e.target.value };
                this.updateAddToCartButton();
            });
        });

        // Quantity controls
        const quantityInput = document.getElementById('product-quantity');
        const increaseBtn = document.getElementById('increase-quantity');
        const decreaseBtn = document.getElementById('decrease-quantity');

        if (quantityInput) {
            quantityInput.addEventListener('change', (e) => {
                this.quantity = parseInt(e.target.value) || 1;
                console.log('📦 Product Detail Cart: Quantity changed:', this.quantity);
            });
        }

        if (increaseBtn) {
            increaseBtn.addEventListener('click', () => {
                this.quantity++;
                if (quantityInput) quantityInput.value = this.quantity;
                console.log('📦 Product Detail Cart: Quantity increased:', this.quantity);
            });
        }

        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', () => {
                if (this.quantity > 1) {
                    this.quantity--;
                    if (quantityInput) quantityInput.value = this.quantity;
                    console.log('📦 Product Detail Cart: Quantity decreased:', this.quantity);
                }
            });
        }

        // Add to cart button
        const addToCartBtn = document.getElementById('add-to-cart-detail');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', () => {
                console.log('📦 Product Detail Cart: Add to cart button clicked');
                this.addToCart();
            });
        }

        // Buy now button
        const buyNowBtn = document.getElementById('buy-now-detail');
        if (buyNowBtn) {
            buyNowBtn.addEventListener('click', () => {
                console.log('📦 Product Detail Cart: Buy now button clicked');
                this.buyNow();
            });
        }
    }

    /**
     * Check if selected options match a variant
     */
    checkVariant() {
        console.log('📦 Product Detail Cart: Checking for matching variant...');
        console.log('📦 Product Detail Cart: Selected options:', this.selectedOptions);

        if (!this.productData || !this.productData.variants) {
            console.log('📦 Product Detail Cart: No variant data available');
            return;
        }

        // Get selected option value IDs
        const selectedValueIds = Object.values(this.selectedOptions)
            .filter(opt => opt.value_id)
            .map(opt => parseInt(opt.value_id))
            .sort();

        console.log('📦 Product Detail Cart: Selected value IDs:', selectedValueIds);

        // Find matching variant
        const matchingVariant = this.productData.variants.find(variant => {
            const variantValueIds = variant.option_values.sort();
            console.log('📦 Product Detail Cart: Comparing with variant:', variant.id, variantValueIds);
            return JSON.stringify(selectedValueIds) === JSON.stringify(variantValueIds);
        });

        if (matchingVariant) {
            console.log('📦 Product Detail Cart: Matching variant found:', matchingVariant);
            this.selectedVariantId = matchingVariant.id;
            this.updatePrice(matchingVariant);
            this.updateStock(matchingVariant);
            this.updateSku(matchingVariant);
        } else {
            console.log('📦 Product Detail Cart: No matching variant found');
            this.selectedVariantId = null;
        }
    }

    /**
     * Update price display
     */
    updatePrice(variant) {
        const priceElement = document.querySelector('[data-price]');
        const comparePriceElement = document.querySelector('[data-compare-price]');

        if (priceElement && variant.price > 0) {
            priceElement.textContent = '$' + (variant.price / 100).toFixed(2);
        }

        if (comparePriceElement) {
            if (variant.compare_price && variant.compare_price > variant.price) {
                comparePriceElement.textContent = '$' + (variant.compare_price / 100).toFixed(2);
                comparePriceElement.style.display = '';
            } else {
                comparePriceElement.style.display = 'none';
            }
        }

        console.log('📦 Product Detail Cart: Price updated to:', variant.price);
    }

    /**
     * Update stock display
     */
    updateStock(variant) {
        const stockElement = document.querySelector('[data-stock]');

        if (stockElement) {
            if (variant.stock > 0) {
                stockElement.className = 'badge bg-success';
                stockElement.textContent = `In Stock (${variant.stock} available)`;
            } else {
                stockElement.className = 'badge bg-danger';
                stockElement.textContent = 'Out of Stock';
            }
        }

        console.log('📦 Product Detail Cart: Stock updated to:', variant.stock);
    }

    /**
     * Update SKU display
     */
    updateSku(variant) {
        const skuElement = document.querySelector('[data-sku]');

        if (skuElement && variant.sku) {
            skuElement.textContent = variant.sku;
        }

        console.log('📦 Product Detail Cart: SKU updated to:', variant.sku);
    }

    /**
     * Update add to cart button state
     */
    updateAddToCartButton() {
        const addToCartBtn = document.getElementById('add-to-cart-detail');
        if (!addToCartBtn) return;

        // Check if all required options are selected
        const requiredOptions = document.querySelectorAll('[data-option-required="true"]');
        let allRequiredSelected = true;

        requiredOptions.forEach(container => {
            const optionId = container.dataset.optionId;
            if (!this.selectedOptions[optionId]) {
                allRequiredSelected = false;
                console.log('📦 Product Detail Cart: Required option not selected:', optionId);
            }
        });

        if (allRequiredSelected) {
            addToCartBtn.disabled = false;
            addToCartBtn.classList.remove('btn-secondary');
            addToCartBtn.classList.add('btn-primary');
            console.log('📦 Product Detail Cart: Add to cart button enabled');
        } else {
            addToCartBtn.disabled = true;
            addToCartBtn.classList.remove('btn-primary');
            addToCartBtn.classList.add('btn-secondary');
            console.log('📦 Product Detail Cart: Add to cart button disabled');
        }
    }

    /**
     * Add product to cart with selected options
     */
    async addToCart() {
        const addToCartBtn = document.getElementById('add-to-cart-detail');
        if (!addToCartBtn) return;

        console.log('📦 Product Detail Cart: Adding to cart...');

        // Disable button and show loading
        addToCartBtn.disabled = true;
        const originalText = addToCartBtn.innerHTML;
        addToCartBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';

        try {
            // Prepare options data
            const options = Object.keys(this.selectedOptions).map(optionId => {
                return {
                    option_id: parseInt(optionId),
                    ...this.selectedOptions[optionId]
                };
            });

            const cartData = {
                product_id: this.productId,
                quantity: this.quantity,
                options: options.length > 0 ? options : undefined,
                variant_id: this.selectedVariantId || undefined
            };

            console.log('📦 Product Detail Cart: Cart data:', cartData);

            // Use global cart manager to add to cart
            if (window.cartManager) {
                const result = await window.cartManager.addToCart(cartData);

                if (result.success) {
                    console.log('📦 Product Detail Cart: Successfully added to cart');
                    // Optionally redirect to cart or show success message
                    // window.location.href = '/cart';
                }
            } else {
                console.error('📦 Product Detail Cart: Cart manager not initialized');
            }

        } catch (error) {
            console.error('📦 Product Detail Cart: Error adding to cart:', error);
        } finally {
            // Re-enable button
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = originalText;
            this.updateAddToCartButton(); // Restore correct state
        }
    }

    /**
     * Buy now (add to cart and redirect to checkout)
     */
    async buyNow() {
        console.log('📦 Product Detail Cart: Buy now clicked');
        await this.addToCart();
        // After successful add, redirect to checkout
        setTimeout(() => {
            window.location.href = '/checkout';
        }, 500);
    }

    /**
     * Get selected options
     */
    getSelectedOptions() {
        return this.selectedOptions;
    }

    /**
     * Reset selected options
     */
    reset() {
        this.selectedOptions = {};
        this.selectedVariantId = null;
        this.quantity = 1;

        document.querySelectorAll('.option-button, .option-color').forEach(button => {
            button.classList.remove('active');
        });

        document.querySelectorAll('.option-radio').forEach(radio => {
            radio.checked = false;
        });

        document.querySelectorAll('.option-select').forEach(select => {
            select.value = '';
        });

        document.querySelectorAll('.product-option-text').forEach(input => {
            input.value = '';
        });

        this.updateAddToCartButton();
    }
}

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        const productDetailElement = document.getElementById('product-detail');
        if (productDetailElement) {
            const productId = productDetailElement.dataset.productId;
            if (productId) {
                console.log('📦 Product Detail Cart: Creating instance for product:', productId);
                window.productDetailCart = new ProductDetailCart(productId);
            }
        }
    });
} else {
    const productDetailElement = document.getElementById('product-detail');
    if (productDetailElement) {
        const productId = productDetailElement.dataset.productId;
        if (productId) {
            console.log('📦 Product Detail Cart: Creating instance for product:', productId);
            window.productDetailCart = new ProductDetailCart(productId);
        }
    }
}
