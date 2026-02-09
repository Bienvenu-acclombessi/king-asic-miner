@extends('client.components.app')

@section('page_title', 'Paiement - King - Fournisseur d\'Or de Mineurs ASIC')

@section('styles')
<style>
.checkout-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.checkout-card.selected {
    border-color: #0aad0a;
    background-color: #f8fff8;
}

.checkout-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e9ecef;
}

.checkout-section:last-child {
    border-bottom: none;
}

.payment-option {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.payment-option:hover {
    border-color: #0aad0a;
}

.payment-option.selected {
    border-color: #0aad0a;
    background-color: #f8fff8;
}

.shipping-method-option {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 0.75rem;
}

.shipping-method-option:hover {
    border-color: #0aad0a;
}

.shipping-method-option.selected {
    border-color: #0aad0a;
    background-color: #f8fff8;
}
</style>
@endsection

@section('content')
<main>
    <!-- Breadcrumb -->
    <div class="mt-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('public.cart.index') }}">Panier</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Paiement</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Section -->
    <section class="mb-lg-14 mb-8 mt-8">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="mb-8">
                        <h1 class="fw-bold mb-0">Paiement</h1>
                        <p class="mb-0">Complétez votre commande en quelques étapes simples.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Addresses & Notes -->
                <div class="col-lg-7 col-md-12">
                    <form id="checkoutForm">
                        @csrf

                        <!-- Shipping Address Section -->
                        <div class="checkout-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">
                                    <i class="bi bi-truck me-2 text-primary"></i>
                                    Adresse de livraison
                                </h4>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal" data-bs-target="#addAddressModal"
                                        data-address-type="shipping">
                                    <i class="bi bi-plus-circle me-1"></i> Ajouter
                                </button>
                            </div>

                            @if($addresses->count() > 0)
                                <div class="row" id="shippingAddressList">
                                    @foreach($addresses as $address)
                                        <div class="col-lg-6 col-12 mb-3">
                                            <div class="checkout-card p-3" data-address-id="{{ $address->id }}">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="shipping_address_id"
                                                           id="shipping_address{{ $address->id }}"
                                                           value="{{ $address->id }}"
                                                           {{ $address->shipping_default ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100"
                                                           for="shipping_address{{ $address->id }}">
                                                        <div class="fw-bold">{{ $address->first_name }} {{ $address->last_name }}</div>
                                                        <address class="mb-0 small">
                                                            {{ $address->line_one }}<br>
                                                            @if($address->line_two) {{ $address->line_two }}<br> @endif
                                                            {{ $address->city }}, {{ $address->postcode }}<br>
                                                            {{ $address->country->name ?? '' }}<br>
                                                            <abbr title="Téléphone">Tél:</abbr> {{ $address->contact_phone }}
                                                        </address>
                                                        @if($address->shipping_default)
                                                            <span class="badge bg-success mt-2">Par défaut</span>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Aucune adresse enregistrée. Veuillez en ajouter une.
                                </div>
                            @endif
                        </div>

                        <!-- Billing Address Section -->
                        <div class="checkout-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">
                                    <i class="bi bi-receipt me-2 text-primary"></i>
                                    Adresse de facturation
                                </h4>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal" data-bs-target="#addAddressModal"
                                        data-address-type="billing"
                                        id="addBillingAddressBtn" style="display: none;">
                                    <i class="bi bi-plus-circle me-1"></i> Ajouter
                                </button>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sameAsShipping" checked>
                                    <label class="form-check-label" for="sameAsShipping">
                                        Utiliser la même adresse que la livraison
                                    </label>
                                </div>
                            </div>

                            <div id="billingAddressSection" style="display: none;">
                                @if($addresses->count() > 0)
                                    <div class="row" id="billingAddressList">
                                        @foreach($addresses as $address)
                                            <div class="col-lg-6 col-12 mb-3">
                                                <div class="checkout-card p-3" data-address-id="{{ $address->id }}">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="billing_address_id"
                                                               id="billing_address{{ $address->id }}"
                                                               value="{{ $address->id }}"
                                                               {{ $address->billing_default ? 'checked' : '' }}>
                                                        <label class="form-check-label w-100"
                                                               for="billing_address{{ $address->id }}">
                                                            <div class="fw-bold">{{ $address->first_name }} {{ $address->last_name }}</div>
                                                            <address class="mb-0 small">
                                                                {{ $address->line_one }}<br>
                                                                @if($address->line_two) {{ $address->line_two }}<br> @endif
                                                                {{ $address->city }}, {{ $address->postcode }}<br>
                                                                {{ $address->country->name ?? '' }}<br>
                                                                <abbr title="Téléphone">Tél:</abbr> {{ $address->contact_phone }}
                                                            </address>
                                                            @if($address->billing_default)
                                                                <span class="badge bg-success mt-2">Par défaut</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Aucune adresse enregistrée. Veuillez en ajouter une.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="checkout-section">
                            <h4 class="mb-3">
                                <i class="bi bi-card-text me-2 text-primary"></i>
                                Notes de commande <span class="text-muted small">(optionnel)</span>
                            </h4>
                            <textarea class="form-control" name="order_notes" id="orderNotes"
                                      rows="4" placeholder="Des instructions spéciales pour votre commande ? Écrivez-les ici..."></textarea>
                            <small class="text-muted">
                                Ces notes seront visibles lors du traitement de votre commande.
                            </small>
                        </div>
                    </form>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-12 col-md-12 offset-lg-1 col-lg-4">
                    <div class="mt-4 mt-lg-0">
                        <div class="card shadow-sm sticky-top" style="top: 20px;">
                            <h5 class="px-4 py-4 bg-light mb-0 border-bottom">
                                <i class="bi bi-bag-check me-2"></i>
                                Récapitulatif
                            </h5>

                            <!-- Products List -->
                            <div class="p-4 border-bottom" style="max-height: 300px; overflow-y: auto;">
                                @foreach($cartData['items'] as $item)
                                    <div class="d-flex mb-3">
                                        <div class="me-3">
                                            @if($item['product']->thumbnail_url)
                                                <img src="{{ $item['product']->thumbnail_url }}"
                                                     alt="{{ $item['product']->name }}"
                                                     class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $item['product']->name }}</h6>
                                            @if(count($item['options']) > 0)
                                                <small class="text-muted d-block">{{ implode(', ', $item['options']) }}</small>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <span class="text-muted small">Qté: {{ $item['quantity'] }}</span>
                                                <span class="fw-bold">${{ number_format($item['line_total'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Subtotal -->
                            <div class="px-4 py-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <span>Sous-total</span>
                                    <span class="fw-bold" id="subtotalAmount">${{ number_format($cartData['subtotal'], 2) }}</span>
                                </div>
                            </div>

                            <!-- Shipping Methods -->
                            <div class="px-4 py-3 border-bottom">
                                <h6 class="mb-3">
                                    <i class="bi bi-truck me-1"></i>
                                    Méthode de livraison
                                </h6>
                                @if($shippingMethods->count() > 0)
                                    <div id="shippingMethodList">
                                        @foreach($shippingMethods as $method)
                                            <div class="shipping-method-option mb-2" data-method-id="{{ $method->id }}" data-price="{{ $method->price }}">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="shipping_method_id"
                                                           id="shipping{{ $method->id }}" value="{{ $method->id }}"
                                                           {{ $loop->first ? 'checked' : '' }} form="checkoutForm">
                                                    <label class="form-check-label w-100 d-flex justify-content-between"
                                                           for="shipping{{ $method->id }}">
                                                        <div>
                                                            <div class="fw-bold small">{{ $method->name }}</div>
                                                            @if($method->estimated_delivery)
                                                                <small class="text-muted">{{ $method->estimated_delivery }}</small>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            @if($method->price_type === 'free')
                                                                <span class="badge bg-success">Gratuit</span>
                                                            @else
                                                                <span class="fw-bold">${{ number_format($method->price, 2) }}</span>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <span>Frais de livraison</span>
                                        <span class="fw-bold" id="shippingAmount">${{ number_format($cartData['shipping_cost'], 2) }}</span>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0 py-2">
                                        <small>Aucune méthode disponible</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Coupon Code -->
                            <div class="px-4 py-3 border-bottom">
                                <h6 class="mb-3">
                                    <i class="bi bi-tag me-1"></i>
                                    Code promo
                                </h6>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="couponCode"
                                           placeholder="Code promo"
                                           value="{{ $cart->coupon_code ?? '' }}">
                                    <button class="btn btn-outline-primary" type="button" id="applyCouponBtn">
                                        Appliquer
                                    </button>
                                    @if($cart->coupon_code)
                                        <button class="btn btn-outline-danger" type="button" id="removeCouponBtn">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @endif
                                </div>
                                <div id="couponMessage" class="mt-2"></div>
                                @if($cartData['discount'] > 0)
                                    <div class="d-flex justify-content-between text-success mt-2">
                                        <span>Réduction</span>
                                        <span class="fw-bold" id="discountAmount">-${{ number_format($cartData['discount'], 2) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Total -->
                            <div class="px-4 py-3 bg-light border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Total</h5>
                                    <h4 class="mb-0 text-primary" id="grandTotal">${{ number_format($cartData['total'], 2) }}</h4>
                                </div>
                            </div>

                            <!-- Payment Methods -->
                            <div class="px-4 py-3 border-bottom">
                                <h6 class="mb-3">
                                    <i class="bi bi-credit-card me-1"></i>
                                    Méthode de paiement
                                </h6>

                                <!-- Coinpal -->
                                <div class="payment-option p-3 mb-2" data-payment-method="coinpal">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               id="paymentCoinpal" value="coinpal" checked form="checkoutForm">
                                        <label class="form-check-label w-100" for="paymentCoinpal">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-currency-bitcoin fs-4 text-warning me-2"></i>
                                                <div>
                                                    <div class="fw-bold">Coinpal</div>
                                                    <small class="text-muted">Payer en crypto</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Bank Transfer -->
                                <div class="payment-option p-3" data-payment-method="bank_transfer">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               id="paymentBankTransfer" value="bank_transfer" form="checkoutForm">
                                        <label class="form-check-label w-100" for="paymentBankTransfer">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-bank fs-4 text-primary me-2"></i>
                                                <div>
                                                    <div class="fw-bold">Virement bancaire</div>
                                                    <small class="text-muted">Instructions par email</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Bank Transfer Info Message -->
                                <div class="alert alert-warning mt-2 mb-0 py-3" id="bankTransferInfo" style="display: none;">
                                    <div class="d-flex">
                                        <i class="bi bi-envelope-check fs-4 me-3 text-warning"></i>
                                        <div>
                                            <div class="fw-bold mb-1">Information importante</div>
                                            <small>
                                                Pour les paiements par virement bancaire, notre équipe vous enverra les détails de paiement par email depuis <strong>info@apexto.com.cn</strong>.
                                                Une fois les fonds reçus, nous procéderons à l'expédition de votre commande.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3 mb-0 py-2">
                                    <small>
                                        <i class="bi bi-info-circle me-1"></i>
                                        Vos données sont protégées. Voir notre
                                        <a href="{{ route('public.policy.privacy') }}" target="_blank">politique de confidentialité</a>.
                                    </small>
                                </div>
                            </div>

                            <!-- Terms & Submit -->
                            <div class="px-4 py-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="terms_accepted"
                                           id="termsAccepted" required form="checkoutForm">
                                    <label class="form-check-label small" for="termsAccepted">
                                        J'accepte les <a href="{{ route('public.policy.terms') }}" target="_blank">conditions générales</a> *
                                    </label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg" id="placeOrderBtn" form="checkoutForm">
                                        <i class="bi bi-lock-fill me-2"></i>
                                        Passer la commande
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Add Address Modal -->
@include('client.pages.checkout.partials.add-address-modal')

@endsection

@section('scripts')
<script>
// Toggle billing address section
document.getElementById('sameAsShipping').addEventListener('change', function() {
    const billingSection = document.getElementById('billingAddressSection');
    const addBillingBtn = document.getElementById('addBillingAddressBtn');

    if (this.checked) {
        billingSection.style.display = 'none';
        addBillingBtn.style.display = 'none';
    } else {
        billingSection.style.display = 'block';
        addBillingBtn.style.display = 'inline-block';
    }
});

// Handle address type when opening modal
document.addEventListener('DOMContentLoaded', function() {
    const addAddressModal = document.getElementById('addAddressModal');

    if (addAddressModal) {
        addAddressModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const addressType = button.getAttribute('data-address-type');
            const typeField = document.getElementById('addressTypeField');
            const modalTitle = document.getElementById('modalAddressTypeLabel');

            if (typeField) {
                typeField.value = addressType || 'shipping';
            }

            if (modalTitle) {
                if (addressType === 'billing') {
                    modalTitle.textContent = 'Ajouter une adresse de facturation';
                } else {
                    modalTitle.textContent = 'Ajouter une adresse de livraison';
                }
            }
        });
    }
});
</script>
<script src="/assets/js/checkout.js"></script>
@endsection
