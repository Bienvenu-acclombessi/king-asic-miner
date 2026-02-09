/**
 * Checkout Page JavaScript
 */

(function() {
    'use strict';

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initAddressSelection();
        initShippingMethodSelection();
        initPaymentMethodSelection();
        initCouponHandlers();
        initAddAddressModal();
        initCheckoutForm();
    });

    /**
     * Address Selection
     */
    function initAddressSelection() {
        const addressCards = document.querySelectorAll('[data-address-id]');
        const addressRadios = document.querySelectorAll('input[name="shipping_address_id"]');

        addressRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all cards
                addressCards.forEach(card => card.classList.remove('selected'));

                // Add selected class to chosen card
                const selectedCard = this.closest('.checkout-card');
                if (selectedCard) {
                    selectedCard.classList.add('selected');
                }

                // Optionally save selection to backend
                saveAddressSelection(this.value);
            });
        });

        // Set initial selected state
        const checkedRadio = document.querySelector('input[name="shipping_address_id"]:checked');
        if (checkedRadio) {
            const selectedCard = checkedRadio.closest('.checkout-card');
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
        }
    }

    /**
     * Save address selection to backend
     */
    function saveAddressSelection(addressId) {
        fetch('/checkout/select-address', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                address_id: addressId,
                type: 'shipping'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Address selected successfully');
            } else {
                console.error('Failed to select address:', data.message);
            }
        })
        .catch(error => {
            console.error('Error selecting address:', error);
        });
    }

    /**
     * Shipping Method Selection
     */
    function initShippingMethodSelection() {
        const shippingOptions = document.querySelectorAll('.shipping-method-option');
        const shippingRadios = document.querySelectorAll('input[name="shipping_method_id"]');

        shippingRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all options
                shippingOptions.forEach(option => option.classList.remove('selected'));

                // Add selected class to chosen option
                const selectedOption = this.closest('.shipping-method-option');
                if (selectedOption) {
                    selectedOption.classList.add('selected');

                    // Update shipping cost in summary
                    const shippingPrice = parseFloat(selectedOption.dataset.price || 0);
                    updateShippingCost(shippingPrice);
                }
            });
        });

        // Set initial selected state
        const checkedRadio = document.querySelector('input[name="shipping_method_id"]:checked');
        if (checkedRadio) {
            const selectedOption = checkedRadio.closest('.shipping-method-option');
            if (selectedOption) {
                selectedOption.classList.add('selected');
            }
        }
    }

    /**
     * Update shipping cost in order summary
     */
    function updateShippingCost(shippingCost) {
        const shippingAmountEl = document.getElementById('shippingAmount');
        if (shippingAmountEl) {
            shippingAmountEl.textContent = '$' + shippingCost.toFixed(2);
        }

        // Recalculate total
        updateGrandTotal();
    }

    /**
     * Payment Method Selection
     */
    function initPaymentMethodSelection() {
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const bankTransferInfo = document.getElementById('bankTransferInfo');

        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all options
                paymentOptions.forEach(option => option.classList.remove('selected'));

                // Add selected class to chosen option
                const selectedOption = this.closest('.payment-option');
                if (selectedOption) {
                    selectedOption.classList.add('selected');
                }

                // Show/hide bank transfer info
                if (bankTransferInfo) {
                    if (this.value === 'bank_transfer') {
                        bankTransferInfo.style.display = 'block';
                    } else {
                        bankTransferInfo.style.display = 'none';
                    }
                }
            });
        });

        // Set initial selected state
        const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
        if (checkedRadio) {
            const selectedOption = checkedRadio.closest('.payment-option');
            if (selectedOption) {
                selectedOption.classList.add('selected');
            }

            // Show bank transfer info if initially selected
            if (bankTransferInfo && checkedRadio.value === 'bank_transfer') {
                bankTransferInfo.style.display = 'block';
            }
        }
    }

    /**
     * Coupon Handlers
     */
    function initCouponHandlers() {
        const applyCouponBtn = document.getElementById('applyCouponBtn');
        const removeCouponBtn = document.getElementById('removeCouponBtn');
        const couponInput = document.getElementById('couponCode');

        if (applyCouponBtn) {
            applyCouponBtn.addEventListener('click', function() {
                const couponCode = couponInput.value.trim();
                if (couponCode) {
                    applyCoupon(couponCode);
                }
            });
        }

        if (removeCouponBtn) {
            removeCouponBtn.addEventListener('click', function() {
                removeCoupon();
            });
        }

        // Allow Enter key to apply coupon
        if (couponInput) {
            couponInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyCouponBtn.click();
                }
            });
        }
    }

    /**
     * Apply coupon code
     */
    function applyCoupon(couponCode) {
        const messageEl = document.getElementById('couponMessage');
        messageEl.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';

        fetch('/cart/apply-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ coupon_code: couponCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageEl.innerHTML = '<div class="alert alert-success py-2"><i class="bi bi-check-circle me-2"></i>' + data.message + '</div>';
                // Reload page to update totals
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                messageEl.innerHTML = '<div class="alert alert-danger py-2"><i class="bi bi-x-circle me-2"></i>' + data.message + '</div>';
            }
        })
        .catch(error => {
            messageEl.innerHTML = '<div class="alert alert-danger py-2">Erreur lors de l\'application du coupon</div>';
            console.error('Error applying coupon:', error);
        });
    }

    /**
     * Remove coupon code
     */
    function removeCoupon() {
        const messageEl = document.getElementById('couponMessage');
        messageEl.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';

        fetch('/cart/remove-coupon', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageEl.innerHTML = '<div class="alert alert-success py-2"><i class="bi bi-check-circle me-2"></i>' + data.message + '</div>';
                // Reload page to update totals
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                messageEl.innerHTML = '<div class="alert alert-danger py-2"><i class="bi bi-x-circle me-2"></i>' + data.message + '</div>';
            }
        })
        .catch(error => {
            messageEl.innerHTML = '<div class="alert alert-danger py-2">Erreur lors de la suppression du coupon</div>';
            console.error('Error removing coupon:', error);
        });
    }

    /**
     * Add Address Modal Handlers
     */
    function initAddAddressModal() {
        const saveBtn = document.getElementById('saveAddressBtn');
        const addForm = document.getElementById('addAddressForm');

        if (saveBtn && addForm) {
            saveBtn.addEventListener('click', function() {
                const formData = new FormData(addForm);
                const data = Object.fromEntries(formData.entries());

                // Validate required fields
                if (!data.first_name || !data.last_name || !data.line_one || !data.city || !data.postcode || !data.contact_phone || !data.country_id) {
                    showAddAddressMessage('Veuillez remplir tous les champs obligatoires', 'danger');
                    return;
                }

                saveAddress(data);
            });
        }
    }

    /**
     * Save new address
     */
    function saveAddress(data) {
        const messageEl = document.getElementById('addAddressMessage');
        const saveBtn = document.getElementById('saveAddressBtn');

        saveBtn.disabled = true;
        messageEl.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';

        fetch('/checkout/add-address', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => ({
                    ok: response.ok,
                    status: response.status,
                    data: data
                }));
            } else {
                throw new Error('La réponse du serveur n\'est pas au format JSON. Vérifiez les logs du serveur.');
            }
        })
        .then(result => {
            if (result.ok && result.data.success) {
                showAddAddressMessage(result.data.message, 'success');
                // Reload page to show new address
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Handle validation errors
                let errorMessage = result.data.message || 'Erreur lors de l\'ajout de l\'adresse';
                if (result.data.errors) {
                    errorMessage += '<br><ul class="mb-0 mt-2">';
                    Object.values(result.data.errors).forEach(errors => {
                        errors.forEach(error => {
                            errorMessage += '<li>' + error + '</li>';
                        });
                    });
                    errorMessage += '</ul>';
                }
                showAddAddressMessage(errorMessage, 'danger');
                saveBtn.disabled = false;
            }
        })
        .catch(error => {
            showAddAddressMessage('Erreur: ' + error.message, 'danger');
            saveBtn.disabled = false;
            console.error('Error saving address:', error);
        });
    }

    /**
     * Show message in add address modal
     */
    function showAddAddressMessage(message, type) {
        const messageEl = document.getElementById('addAddressMessage');
        messageEl.innerHTML = '<div class="alert alert-' + type + ' py-2"><i class="bi bi-' + (type === 'success' ? 'check-circle' : 'x-circle') + ' me-2"></i>' + message + '</div>';
    }

    /**
     * Checkout Form Submission
     */
    function initCheckoutForm() {
        const checkoutForm = document.getElementById('checkoutForm');

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate form
                if (!validateCheckoutForm()) {
                    return;
                }

                const formData = new FormData(checkoutForm);
                const data = Object.fromEntries(formData.entries());

                placeOrder(data);
            });
        }
    }

    /**
     * Validate checkout form
     */
    function validateCheckoutForm() {
        const addressSelected = document.querySelector('input[name="shipping_address_id"]:checked');
        const shippingSelected = document.querySelector('input[name="shipping_method_id"]:checked');
        const paymentSelected = document.querySelector('input[name="payment_method"]:checked');
        const termsAccepted = document.getElementById('termsAccepted');

        if (!addressSelected) {
            alert('Veuillez sélectionner une adresse de livraison');
            return false;
        }

        if (!shippingSelected) {
            alert('Veuillez sélectionner une méthode de livraison');
            return false;
        }

        if (!paymentSelected) {
            alert('Veuillez sélectionner une méthode de paiement');
            return false;
        }

        if (!termsAccepted || !termsAccepted.checked) {
            alert('Veuillez accepter les conditions générales');
            return false;
        }

        return true;
    }

    /**
     * Place order
     */
    function placeOrder(data) {
        const placeOrderBtn = document.getElementById('placeOrderBtn');
        const originalBtnText = placeOrderBtn.innerHTML;

        placeOrderBtn.disabled = true;
        placeOrderBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Traitement en cours...';

        fetch('/checkout/place-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert(data.message);

                // Redirect based on payment method
                if (data.order_reference) {
                    // Redirect to order confirmation or thank you page
                    window.location.href = '/account/orders'; // Adjust this URL as needed
                }
            } else {
                alert(data.message || 'Erreur lors de la création de la commande');
                placeOrderBtn.disabled = false;
                placeOrderBtn.innerHTML = originalBtnText;
            }
        })
        .catch(error => {
            alert('Erreur lors de la création de la commande. Veuillez réessayer.');
            placeOrderBtn.disabled = false;
            placeOrderBtn.innerHTML = originalBtnText;
            console.error('Error placing order:', error);
        });
    }

    /**
     * Update grand total
     */
    function updateGrandTotal() {
        const subtotalEl = document.getElementById('subtotalAmount');
        const shippingEl = document.getElementById('shippingAmount');
        const discountEl = document.getElementById('discountAmount');
        const grandTotalEl = document.getElementById('grandTotal');

        if (!subtotalEl || !shippingEl || !grandTotalEl) return;

        const subtotal = parseFloat(subtotalEl.textContent.replace('$', '').replace(',', ''));
        const shipping = parseFloat(shippingEl.textContent.replace('$', '').replace(',', ''));
        const discount = discountEl ? parseFloat(discountEl.textContent.replace('-$', '').replace(',', '')) : 0;

        const grandTotal = subtotal + shipping - discount;
        grandTotalEl.textContent = '$' + grandTotal.toFixed(2);
    }

})();
