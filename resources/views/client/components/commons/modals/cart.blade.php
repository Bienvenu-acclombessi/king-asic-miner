<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <div class="text-start">
            <h5 id="offcanvasRightLabel" class="mb-0 fs-4">Panier</h5>
            <small><span class="cart-count">0</span> article(s)</small>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Empty Cart Message -->
        <div id="cart-empty-message" class="text-center py-5" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <h5 class="mb-2">Votre panier est vide</h5>
            <p class="text-muted">Ajoutez des produits à votre panier pour continuer</p>
            <a href="/" class="btn btn-primary mt-3" data-bs-dismiss="offcanvas">Continuer vos achats</a>
        </div>

        <!-- Cart Items -->
        <div id="cart-items-container">
            <ul class="list-group list-group-flush" id="cart-items-list">
                <!-- Items will be inserted here dynamically -->
            </ul>

            <!-- Cart Summary -->
            <div class="border-top pt-4 mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>Sous-total</span>
                    <span class="fw-bold cart-subtotal">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="fs-4 fw-bold">Total</span>
                    <span class="fs-4 fw-bold cart-total">$0.00</span>
                </div>

                <div class="d-grid gap-2">
                    <a href="/checkout" class="btn btn-primary btn-lg">
                        <i class="bi bi-credit-card me-2"></i>Passer la commande
                    </a>
                    <a href="/cart" class="btn btn-outline-dark">
                        Voir le panier complet
                    </a>
                    <button type="button" class="btn btn-link text-danger" id="clear-cart-offcanvas-btn">
                        <i class="bi bi-trash me-1"></i>Vider le panier
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Clear cart button in offcanvas
document.addEventListener('click', (e) => {
    if (e.target.closest('#clear-cart-offcanvas-btn')) {
        e.preventDefault();
        if (window.cartManager) {
            window.cartManager.clearCart();
        }
    }
});
</script>
