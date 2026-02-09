<section class="mt-8 mb-14">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Panier</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="mb-lg-14 mb-8">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Heading -->
                <div class="card mb-6 border-0">
                    <div class="card-body p-6">
                        <h1 class="mb-0 h2">Panier d'achat</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty Cart Message -->
        <div id="cart-page-empty" class="row" style="display: none;">
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-body text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-4">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <h3 class="mb-3">Votre panier est vide</h3>
                        <p class="text-muted mb-4">Vous n'avez aucun article dans votre panier. Commencez vos achats maintenant!</p>
                        <a href="/" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop me-2"></i>Continuer vos achats
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Content -->
        <div id="cart-page-content">
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <div class="card mb-4">
                        <div class="card-body p-6">
                            <!-- Cart Items Table -->
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="border-bottom">Produit</th>
                                            <th scope="col" class="border-bottom text-center">Prix</th>
                                            <th scope="col" class="border-bottom text-center">Quantité</th>
                                            <th scope="col" class="border-bottom text-end">Total</th>
                                            <th scope="col" class="border-bottom"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-page-items">
                                        <!-- Items will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="/" class="btn btn-outline-dark">
                            <i class="bi bi-arrow-left me-2"></i>Continuer vos achats
                        </a>
                        <button type="button" class="btn btn-outline-danger" id="clear-cart-page-btn">
                            <i class="bi bi-trash me-2"></i>Vider le panier
                        </button>
                    </div>
                </div>

                <div class="col-lg-4 col-md-5">
                    <!-- Cart Summary -->
                    <div class="card mb-4">
                        <div class="card-body p-6">
                            <h4 class="mb-4">Résumé</h4>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Sous-total (<span class="cart-count">0</span> articles)</span>
                                <span class="cart-subtotal">$0.00</span>
                            </div>

                            <!-- Discount section -->
                            <div id="cart-discount-section" class="d-flex justify-content-between mb-2 text-success" style="display: none !important;">
                                <span>
                                    Réduction (<span id="cart-coupon-code"></span>)
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" id="remove-coupon-btn" style="font-size: 0.8rem;">
                                        ✕
                                    </button>
                                </span>
                                <span class="cart-discount">-$0.00</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Livraison</span>
                                <span id="cart-shipping-cost" class="cart-shipping">$0.00</span>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 mb-0">Total</span>
                                <span class="h5 mb-0 cart-total">$0.00</span>
                            </div>

                            <!-- Shipping Method Section -->
                            <div class="mb-4">
                                <h6 class="mb-3">Méthode de livraison</h6>
                                <div id="shipping-methods-container">
                                    <div class="text-center py-3">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        <small class="text-muted">Chargement...</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Promo Code Section -->
                            <div class="mb-4">
                                <!-- Toggle Button -->
                                <button type="button" class="btn btn-link p-0 text-decoration-none" id="toggle-coupon-btn">
                                    <i class="bi bi-ticket-perforated me-2"></i>Avez-vous un code promo ?
                                </button>

                                <!-- Coupon Form (hidden by default) -->
                                <div id="coupon-form-container" class="mt-3" style="display: none;">
                                    <form id="coupon-form">
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                   id="coupon-input"
                                                   class="form-control form-control-sm"
                                                   placeholder="Entrez votre code"
                                                   style="text-transform: uppercase;">
                                            <button class="btn btn-outline-dark btn-sm" type="submit" id="apply-coupon-btn">
                                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                                                Appliquer
                                            </button>
                                        </div>
                                        <div id="coupon-message" class="alert d-none p-2" role="alert" style="font-size: 0.875rem;"></div>
                                    </form>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Checkout Button -->
                            <div class="d-grid">
                                <a href="/checkout" class="btn btn-primary btn-lg">
                                    <i class="bi bi-credit-card me-2"></i>Passer la commande
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="mt-4">
                        <h6 class="mb-3">Méthodes de paiement acceptées</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-secondary py-2 px-3">USD</span>
                            <span class="badge bg-secondary py-2 px-3">USDT (TRC 20)</span>
                            <span class="badge bg-secondary py-2 px-3">BTC</span>
                            <span class="badge bg-secondary py-2 px-3">ETH</span>
                            <span class="badge bg-secondary py-2 px-3">LTC</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Clear cart button on cart page
document.addEventListener('click', (e) => {
    if (e.target.closest('#clear-cart-page-btn')) {
        e.preventDefault();
        if (window.cartManager) {
            window.cartManager.clearCart();
        }
    }
});

// Toggle coupon form visibility
document.addEventListener('DOMContentLoaded', () => {
    const toggleCouponBtn = document.getElementById('toggle-coupon-btn');
    const couponFormContainer = document.getElementById('coupon-form-container');

    if (toggleCouponBtn && couponFormContainer) {
        toggleCouponBtn.addEventListener('click', () => {
            if (couponFormContainer.style.display === 'none') {
                couponFormContainer.style.display = 'block';
                toggleCouponBtn.innerHTML = '<i class="bi bi-chevron-up me-2"></i>Masquer le code promo';
            } else {
                couponFormContainer.style.display = 'none';
                toggleCouponBtn.innerHTML = '<i class="bi bi-ticket-perforated me-2"></i>Avez-vous un code promo ?';
            }
        });
    }
});

// Coupon form submission
document.addEventListener('DOMContentLoaded', () => {
    const couponForm = document.getElementById('coupon-form');
    const couponInput = document.getElementById('coupon-input');
    const applyBtn = document.getElementById('apply-coupon-btn');
    const spinner = applyBtn?.querySelector('.spinner-border');
    const couponMessage = document.getElementById('coupon-message');
    const removeCouponBtn = document.getElementById('remove-coupon-btn');

    if (couponForm) {
        couponForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const couponCode = couponInput.value.trim();

            if (!couponCode) {
                couponMessage.className = 'alert alert-warning';
                couponMessage.textContent = 'Veuillez entrer un code promo';
                couponMessage.classList.remove('d-none');
                return;
            }

            // Show loading
            applyBtn.disabled = true;
            spinner?.classList.remove('d-none');
            couponMessage.classList.add('d-none');

            // Apply coupon via cart manager
            if (window.cartManager) {
                await window.cartManager.applyCoupon(couponCode);
                couponInput.value = '';
            }

            // Hide loading
            applyBtn.disabled = false;
            spinner?.classList.add('d-none');
        });
    }

    // Remove coupon button
    if (removeCouponBtn) {
        removeCouponBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            if (window.cartManager) {
                await window.cartManager.removeCoupon();
                couponInput.value = '';
            }
        });
    }
});
</script>
