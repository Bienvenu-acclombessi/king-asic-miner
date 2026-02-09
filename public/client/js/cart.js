/**
 * Cart Management System
 * Handles adding/removing items, updating quantities, and displaying cart data
 */

console.log('======================================');
console.log('CART.JS FILE LOADED SUCCESSFULLY!');
console.log('======================================');

class CartManager {
    constructor() {
        this.cart = {
            items: [],
            items_count: 0,
            subtotal: 0,
            total: 0
        };

        this.init();
    }

    /**
     * Initialize cart system
     */
    async init() {
        console.log('🛒 Cart Manager: Initializing...');

        // Load cart data on page load
        await this.loadCart();

        // Setup event listeners
        this.setupEventListeners();

        // Update UI
        this.updateCartUI();

        console.log('🛒 Cart Manager: Initialized successfully', this.cart);
    }

    /**
     * Setup global event listeners
     */
    setupEventListeners() {
        console.log('🛒 Cart Manager: Setting up event listeners...');

        // Add to cart buttons on product cards
        document.addEventListener('click', (e) => {
            const addToCartBtn = e.target.closest('.add-to-cart-btn');
            if (addToCartBtn) {
                console.log('🛒 Cart Manager: Add to cart button clicked', addToCartBtn);
                e.preventDefault();
                e.stopPropagation(); // Prevent click from bubbling to parent link
                this.handleQuickAddToCart(addToCartBtn);
            }

            // Cart quantity controls
            const increaseBtn = e.target.closest('.cart-quantity-increase');
            const decreaseBtn = e.target.closest('.cart-quantity-decrease');
            const removeBtn = e.target.closest('.cart-remove-item');

            if (increaseBtn) {
                e.preventDefault();
                const lineId = increaseBtn.dataset.lineId;
                this.changeQuantity(lineId, 1);
            }

            if (decreaseBtn) {
                e.preventDefault();
                const lineId = decreaseBtn.dataset.lineId;
                this.changeQuantity(lineId, -1);
            }

            if (removeBtn) {
                e.preventDefault();
                const lineId = removeBtn.dataset.lineId;
                this.removeItem(lineId);
            }
        });

        // Quantity input direct change
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('cart-quantity-input')) {
                const lineId = e.target.dataset.lineId;
                const quantity = parseInt(e.target.value) || 1;
                this.updateQuantity(lineId, quantity);
            }
        });
    }

    /**
     * Load cart from server
     */
    async loadCart() {
        console.log('🛒 Cart Manager: Loading cart...');
        try {
            const response = await fetch('/cart/get', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            console.log('🛒 Cart Manager: Response status:', response.status);
            const data = await response.json();
            console.log('🛒 Cart Manager: Response data:', data);

            if (data.success) {
                this.cart = data.cart;
                this.updateCartUI();
                console.log('🛒 Cart Manager: Cart loaded successfully', this.cart);
            } else {
                console.error('🛒 Cart Manager: Failed to load cart', data);
            }
        } catch (error) {
            console.error('🛒 Cart Manager: Error loading cart:', error);
        }
    }

    /**
     * Handle quick add to cart from product cards
     * Checks if product has required options first
     */
    async handleQuickAddToCart(button) {
        const productId = button.dataset.productId;
        const quantity = parseInt(button.dataset.quantity || 1);

        console.log('🛒 Cart Manager: Handling quick add to cart', { productId, quantity });

        // Disable button and show loading
        button.disabled = true;
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Ajout...';

        try {
            // Check if product can be added directly
            console.log('🛒 Cart Manager: Checking product requirements...');
            const checkResponse = await fetch(`/cart/check-product/${productId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            console.log('🛒 Cart Manager: Check response status:', checkResponse.status);
            const checkData = await checkResponse.json();
            console.log('🛒 Cart Manager: Check data:', checkData);

            if (!checkData.can_add_directly) {
                console.log('🛒 Cart Manager: Product has required options, redirecting...');
                // Product has required options - redirect to product page
                this.showNotification('Ce produit nécessite des options', 'info');
                setTimeout(() => {
                    window.location.href = checkData.product_url;
                }, 1000);
                return;
            }

            // Product can be added directly
            console.log('🛒 Cart Manager: Product can be added directly, adding to cart...');
            await this.addToCart({
                product_id: productId,
                quantity: quantity
            });

        } catch (error) {
            console.error('🛒 Cart Manager: Error adding to cart:', error);
            this.showNotification('Erreur lors de l\'ajout au panier', 'error');
        } finally {
            // Re-enable button
            button.disabled = false;
            button.innerHTML = originalText;
        }
    }

    /**
     * Add item to cart
     * @param {Object} data - { product_id, variant_id?, quantity, options? }
     */
    async addToCart(data) {
        console.log('🛒 Cart Manager: Adding to cart with data:', data);
        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify(data)
            });

            console.log('🛒 Cart Manager: Add response status:', response.status);
            const result = await response.json();
            console.log('🛒 Cart Manager: Add response data:', result);

            if (result.success) {
                this.cart = result.cart;
                this.updateCartUI();
                this.showNotification(result.message || 'Produit ajouté au panier', 'success');

                // Trigger custom event
                window.dispatchEvent(new CustomEvent('cart:updated', { detail: this.cart }));
            } else {
                // Check if redirect is needed
                if (result.redirect) {
                    this.showNotification(result.message, 'info');
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1500);
                } else {
                    this.showNotification(result.message || 'Erreur lors de l\'ajout', 'error');
                }
            }

            return result;

        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification('Erreur lors de l\'ajout au panier', 'error');
            return { success: false };
        }
    }

    /**
     * Update item quantity
     */
    async updateQuantity(lineId, quantity) {
        try {
            const response = await fetch(`/cart/update/${lineId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ quantity })
            });

            const result = await response.json();

            if (result.success) {
                this.cart = result.cart;
                this.updateCartUI();
                this.showNotification(result.message, 'success');

                window.dispatchEvent(new CustomEvent('cart:updated', { detail: this.cart }));
            } else {
                this.showNotification(result.message || 'Erreur lors de la mise à jour', 'error');
            }

        } catch (error) {
            console.error('Error updating quantity:', error);
            this.showNotification('Erreur lors de la mise à jour', 'error');
        }
    }

    /**
     * Change quantity by delta (increase/decrease)
     */
    async changeQuantity(lineId, delta) {
        const item = this.cart.items.find(i => i.id == lineId);
        if (!item) return;

        const newQuantity = Math.max(0, item.quantity + delta);
        await this.updateQuantity(lineId, newQuantity);
    }

    /**
     * Remove item from cart
     */
    async removeItem(lineId) {
        if (!confirm('Êtes-vous sûr de vouloir retirer ce produit ?')) {
            return;
        }

        try {
            const response = await fetch(`/cart/remove/${lineId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const result = await response.json();

            if (result.success) {
                this.cart = result.cart;
                this.updateCartUI();
                this.showNotification(result.message, 'success');

                window.dispatchEvent(new CustomEvent('cart:updated', { detail: this.cart }));
            } else {
                this.showNotification(result.message || 'Erreur lors de la suppression', 'error');
            }

        } catch (error) {
            console.error('Error removing item:', error);
            this.showNotification('Erreur lors de la suppression', 'error');
        }
    }

    /**
     * Clear entire cart
     */
    async clearCart() {
        if (!confirm('Êtes-vous sûr de vouloir vider le panier ?')) {
            return;
        }

        try {
            const response = await fetch('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const result = await response.json();

            if (result.success) {
                this.cart = result.cart;
                this.updateCartUI();
                this.showNotification(result.message, 'success');

                window.dispatchEvent(new CustomEvent('cart:updated', { detail: this.cart }));
            }

        } catch (error) {
            console.error('Error clearing cart:', error);
            this.showNotification('Erreur lors du vidage du panier', 'error');
        }
    }

    /**
     * Update cart UI elements (badge, total, etc.)
     */
    updateCartUI() {
        console.log('🛒 Cart Manager: Updating UI with cart:', this.cart);

        // Update cart count badges
        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = this.cart.items_count || 0;

            // Hide badge if empty
            if (this.cart.items_count === 0) {
                el.style.display = 'none';
            } else {
                el.style.display = '';
            }
        });

        // Update cart total
        document.querySelectorAll('.cart-total').forEach(el => {
            el.textContent = this.formatPrice(this.cart.total);
        });

        // Update cart subtotal
        document.querySelectorAll('.cart-subtotal').forEach(el => {
            el.textContent = this.formatPrice(this.cart.subtotal);
        });

        // Update shipping cost
        document.querySelectorAll('.cart-shipping').forEach(el => {
            el.textContent = this.formatPrice(this.cart.shipping_cost || 0);
        });

        // Update discount display
        const discountSection = document.getElementById('cart-discount-section');
        const discountAmountEl = document.querySelector('.cart-discount');
        const couponCodeEl = document.getElementById('cart-coupon-code');

        if (this.cart.coupon && this.cart.discount > 0) {
            // Show discount section
            if (discountSection) {
                discountSection.style.display = 'flex';
                discountSection.style.removeProperty('display'); // Override inline style
            }
            if (discountAmountEl) {
                discountAmountEl.textContent = '-' + this.formatPrice(this.cart.discount);
            }
            if (couponCodeEl) {
                couponCodeEl.textContent = this.cart.coupon.code;
            }
        } else {
            // Hide discount section
            if (discountSection) {
                discountSection.style.display = 'none';
            }
        }

        // Update offcanvas
        this.updateCartOffcanvas();

        // Update cart page
        this.updateCartPage();

        // Trigger custom event for other components
        window.dispatchEvent(new CustomEvent('cart:ui-updated', { detail: this.cart }));
    }

    /**
     * Update cart offcanvas
     */
    updateCartOffcanvas() {
        console.log('🛒 Cart Manager: Updating offcanvas with cart:', this.cart);

        const emptyMessage = document.getElementById('cart-empty-message');
        const itemsContainer = document.getElementById('cart-items-container');
        const itemsList = document.getElementById('cart-items-list');

        if (!emptyMessage || !itemsContainer || !itemsList) {
            console.log('🛒 Cart Manager: Offcanvas elements not found');
            return;
        }

        if (!this.cart || !this.cart.items || this.cart.items.length === 0) {
            // Show empty message
            emptyMessage.style.display = 'block';
            itemsContainer.style.display = 'none';
            console.log('🛒 Cart Manager: Showing empty message');
            return;
        }

        // Hide empty message and show items
        emptyMessage.style.display = 'none';
        itemsContainer.style.display = 'block';

        // Clear current items
        itemsList.innerHTML = '';

        console.log('🛒 Cart Manager: Adding items to offcanvas:', this.cart.items.length);

        // Add each item
        this.cart.items.forEach((item, index) => {
            console.log(`🛒 Cart Manager: Adding item ${index + 1}:`, item);

            const itemHtml = `
                <li class="list-group-item py-3 ps-0 border-top">
                    <div class="row align-items-center">
                        <div class="col-6 col-md-6">
                            <div class="d-flex">
                                <img src="${item.thumbnail || '/assets/kingshop/assets/images/products/product-img-1.jpg'}"
                                     alt="${item.product_name}"
                                     class="icon-shape icon-xxl"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="ms-3">
                                    <a href="/product/${item.product_slug}" class="text-inherit">
                                        <h6 class="mb-0 small">${item.product_name}</h6>
                                    </a>
                                    ${item.sku ? `<small class="text-muted">SKU: ${item.sku}</small><br>` : ''}
                                    ${item.options && item.options.length > 0 ? `
                                        <small class="text-muted">${item.options.join(', ')}</small>
                                    ` : ''}
                                    <div class="mt-2 small lh-1">
                                        <a href="#!" class="text-decoration-none text-inherit cart-remove-item" data-line-id="${item.id}">
                                            <span class="me-1 align-text-bottom">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </span>
                                            <span class="text-danger">Retirer</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 col-md-3">
                            <div class="input-group input-spinner">
                                <button type="button" class="btn btn-sm btn-outline-secondary cart-quantity-decrease" data-line-id="${item.id}">-</button>
                                <input type="number"
                                       class="form-control form-control-sm text-center cart-quantity-input"
                                       value="${item.quantity}"
                                       data-line-id="${item.id}"
                                       min="1"
                                       style="max-width: 50px;">
                                <button type="button" class="btn btn-sm btn-outline-secondary cart-quantity-increase" data-line-id="${item.id}">+</button>
                            </div>
                        </div>
                        <div class="col-3 text-end">
                            <span class="fw-bold">$${item.line_total.toFixed(2)}</span>
                        </div>
                    </div>
                </li>
            `;
            itemsList.insertAdjacentHTML('beforeend', itemHtml);
        });

        console.log('🛒 Cart Manager: Offcanvas updated successfully');
    }

    /**
     * Update cart page
     */
    updateCartPage() {
        console.log('🛒 Cart Manager: Updating cart page with cart:', this.cart);

        const emptySection = document.getElementById('cart-page-empty');
        const contentSection = document.getElementById('cart-page-content');
        const itemsContainer = document.getElementById('cart-page-items');

        if (!emptySection || !contentSection || !itemsContainer) {
            console.log('🛒 Cart Manager: Cart page elements not found');
            return;
        }

        if (!this.cart || !this.cart.items || this.cart.items.length === 0) {
            // Show empty message
            emptySection.style.display = 'block';
            contentSection.style.display = 'none';
            console.log('🛒 Cart Manager: Showing empty message on cart page');
            return;
        }

        // Hide empty message and show content
        emptySection.style.display = 'none';
        contentSection.style.display = 'block';

        // Clear current items
        itemsContainer.innerHTML = '';

        console.log('🛒 Cart Manager: Adding items to cart page:', this.cart.items.length);

        // Add each item
        this.cart.items.forEach((item, index) => {
            console.log(`🛒 Cart Manager: Adding item ${index + 1} to page:`, item);

            const itemHtml = `
                <tr>
                    <td class="py-4">
                        <div class="d-flex align-items-center">
                            <img src="${item.thumbnail || '/assets/kingshop/assets/images/products/product-img-1.jpg'}"
                                 alt="${item.product_name}"
                                 class="rounded"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="ms-3">
                                <a href="/product/${item.product_slug}" class="text-inherit">
                                    <h6 class="mb-1">${item.product_name}</h6>
                                </a>
                                ${item.sku ? `<small class="text-muted">SKU: ${item.sku}</small><br>` : ''}
                                ${item.options && item.options.length > 0 ? `
                                    <small class="text-muted">${item.options.join(' • ')}</small>
                                ` : ''}
                            </div>
                        </div>
                    </td>
                    <td class="py-4 text-center">
                        <span class="text-dark">$${item.unit_price.toFixed(2)}</span>
                    </td>
                    <td class="py-4 text-center">
                        <div class="input-group input-spinner mx-auto" style="width: 140px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary cart-quantity-decrease" data-line-id="${item.id}">-</button>
                            <input type="number"
                                   class="form-control form-control-sm text-center cart-quantity-input"
                                   value="${item.quantity}"
                                   data-line-id="${item.id}"
                                   min="1"
                                   style="max-width: 60px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary cart-quantity-increase" data-line-id="${item.id}">+</button>
                        </div>
                    </td>
                    <td class="py-4 text-end">
                        <span class="fw-bold text-dark">$${item.line_total.toFixed(2)}</span>
                    </td>
                    <td class="py-4 text-end">
                        <button type="button" class="btn btn-sm btn-link text-danger cart-remove-item" data-line-id="${item.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
        });

        // Load shipping methods for cart page
        this.loadShippingMethods();

        console.log('🛒 Cart Manager: Cart page updated successfully');
    }

    /**
     * Format price for display
     */
    formatPrice(price) {
        return '$' + parseFloat(price || 0).toFixed(2);
    }

    /**
     * Show notification message
     */
    showNotification(message, type = 'info') {
        // Check if Bootstrap toast exists
        const toastContainer = document.querySelector('.toast-container');

        if (toastContainer) {
            const toastId = 'toast-' + Date.now();
            const bgClass = {
                'success': 'bg-success',
                'error': 'bg-danger',
                'info': 'bg-info',
                'warning': 'bg-warning'
            }[type] || 'bg-info';

            const toastHTML = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
            toast.show();

            // Remove toast element after hidden
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        } else {
            // Fallback to alert
            alert(message);
        }
    }

    /**
     * Get CSRF token
     */
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    /**
     * Get current cart data
     */
    getCart() {
        return this.cart;
    }

    /**
     * Apply coupon code
     */
    async applyCoupon(couponCode) {
        if (!couponCode || couponCode.trim() === '') {
            this.showNotification('Veuillez entrer un code promo', 'info');
            return;
        }

        console.log('🛒 Cart Manager: Applying coupon:', couponCode);

        try {
            const response = await fetch('/cart/apply-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ coupon_code: couponCode })
            });

            const data = await response.json();

            if (data.success) {
                this.cart = data.cart;
                this.updateCartUI();
                this.showNotification(data.message, 'success');
                console.log('🛒 Cart Manager: Coupon applied successfully', this.cart);
            } else {
                this.showNotification(data.message || 'Erreur lors de l\'application du code promo', 'error');
                console.error('🛒 Cart Manager: Failed to apply coupon', data);
            }
        } catch (error) {
            console.error('🛒 Cart Manager: Error applying coupon:', error);
            this.showNotification('Erreur lors de l\'application du code promo', 'error');
        }
    }

    /**
     * Remove coupon
     */
    async removeCoupon() {
        console.log('🛒 Cart Manager: Removing coupon');

        try {
            const response = await fetch('/cart/remove-coupon', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            const data = await response.json();

            if (data.success) {
                this.cart = data.cart;
                this.updateCartUI();
                this.showNotification(data.message, 'success');
                console.log('🛒 Cart Manager: Coupon removed successfully', this.cart);
            } else {
                this.showNotification(data.message || 'Erreur lors de la suppression du code promo', 'error');
                console.error('🛒 Cart Manager: Failed to remove coupon', data);
            }
        } catch (error) {
            console.error('🛒 Cart Manager: Error removing coupon:', error);
            this.showNotification('Erreur lors de la suppression du code promo', 'error');
        }
    }

    /**
     * Load available shipping methods
     */
    async loadShippingMethods() {
        console.log('🚚 Cart Manager: Loading shipping methods...');

        const container = document.getElementById('shipping-methods-container');
        if (!container) {
            console.log('🚚 Cart Manager: Shipping methods container not found');
            return;
        }

        try {
            const response = await fetch('/cart/shipping-methods', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();

            if (data.success && data.shipping_methods) {
                console.log('🚚 Cart Manager: Shipping methods loaded:', data.shipping_methods);
                this.renderShippingMethods(data.shipping_methods);
            } else {
                container.innerHTML = '<div class="alert alert-warning">Aucune méthode de livraison disponible</div>';
            }
        } catch (error) {
            console.error('🚚 Cart Manager: Error loading shipping methods:', error);
            container.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement des méthodes de livraison</div>';
        }
    }

    /**
     * Render shipping methods in UI
     */
    renderShippingMethods(methods) {
        const container = document.getElementById('shipping-methods-container');
        if (!container || !methods || methods.length === 0) {
            container.innerHTML = '<p class="text-muted small">Aucune méthode de livraison disponible pour le moment.</p>';
            return;
        }

        let currentMethodId = this.cart.shipping_method?.id;

        // Auto-select first method if none selected
        const shouldAutoSelect = !currentMethodId && methods.length > 0;
        if (shouldAutoSelect) {
            currentMethodId = methods[0].id;
        }

        let html = '<div class="list-group list-group-flush">';

        methods.forEach((method, index) => {
            const isSelected = currentMethodId === method.id;
            const priceText = method.price_type === 'free' ? 'GRATUIT' : `$${method.cost.toFixed(2)}`;

            html += `
                <label class="list-group-item list-group-item-action ${isSelected ? 'active' : ''}" style="cursor: pointer; border-left: none; border-right: none; ${index === 0 ? 'border-top: none;' : ''}">
                    <div class="d-flex align-items-center">
                        <input class="form-check-input me-2" type="radio" name="shipping_method"
                               value="${method.id}" ${isSelected ? 'checked' : ''}
                               data-method-id="${method.id}">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="d-block">${method.name}</strong>
                                    ${method.estimated_delivery ? `<small class="text-muted">${method.estimated_delivery}</small>` : ''}
                                </div>
                                <div class="text-end">
                                    <strong>${priceText}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            `;
        });

        html += '</div>';
        container.innerHTML = html;

        // Add event listeners to radio buttons
        container.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                const methodId = parseInt(e.target.dataset.methodId);
                this.selectShippingMethod(methodId);
            });
        });

        // Auto-select first method
        if (shouldAutoSelect) {
            this.selectShippingMethod(methods[0].id);
        }

        console.log('🚚 Cart Manager: Shipping methods rendered');
    }

    /**
     * Select a shipping method
     */
    async selectShippingMethod(methodId) {
        console.log('🚚 Cart Manager: Selecting shipping method:', methodId);

        try {
            const response = await fetch('/cart/set-shipping-method', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    shipping_method_id: methodId
                }),
            });

            const data = await response.json();

            if (data.success) {
                this.cart = data.cart;
                this.updateCartUI();
                this.showNotification(data.message, 'success');
                console.log('🚚 Cart Manager: Shipping method selected successfully', this.cart);
            } else {
                this.showNotification(data.message || 'Erreur lors de la sélection', 'error');
                console.error('🚚 Cart Manager: Failed to select shipping method', data);
            }
        } catch (error) {
            console.error('🚚 Cart Manager: Error selecting shipping method:', error);
            this.showNotification('Erreur lors de la sélection de la méthode de livraison', 'error');
        }
    }
}

// Initialize cart manager when DOM is ready
let cartManager;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        cartManager = new CartManager();
        window.cartManager = cartManager; // Make globally accessible
    });
} else {
    cartManager = new CartManager();
    window.cartManager = cartManager;
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartManager;
}
