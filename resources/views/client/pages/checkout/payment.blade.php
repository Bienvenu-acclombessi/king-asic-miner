@extends('client.components.app')

@section('page_title', 'Finaliser le paiement')

@section('main')
<section class="py-8 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Order Header -->
                <div class="mb-6 text-center">
                    <h2>Finaliser le paiement</h2>
                    <p class="text-muted">Commande #{{ $order->reference }}</p>
                </div>

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Payment Instructions -->
                    <div class="col-lg-8">
                        @if($order->payment_method === 'coinpal')
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0"><i class="bi bi-currency-bitcoin"></i> Paiement CoinPal</h4>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i>
                                        Veuillez compléter votre paiement via CoinPal pour finaliser votre commande.
                                    </div>

                                    <div class="text-center py-5">
                                        <div class="mb-4">
                                            <i class="bi bi-currency-bitcoin text-primary" style="font-size: 4rem;"></i>
                                        </div>
                                        <h4 class="mb-3">Montant à payer</h4>
                                        <h2 class="text-primary mb-4">${{ number_format($order->total / 100, 2) }}</h2>
                                        <p class="text-muted mb-4">Référence: <strong>{{ $order->reference }}</strong></p>

                                        <div class="alert alert-warning">
                                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Important :</h6>
                                            <p class="mb-0">L'interface de paiement CoinPal sera intégrée ici. Vous serez redirigé vers la plateforme de paiement sécurisée.</p>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-success btn-lg">
                                            <i class="bi bi-wallet2"></i> Payer avec CoinPal
                                        </button>
                                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left"></i> Retour à mes commandes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0"><i class="bi bi-credit-card"></i> Finaliser le paiement</h4>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i>
                                        Veuillez compléter votre paiement pour finaliser votre commande.
                                    </div>

                                    <div class="text-center py-5">
                                        <p class="text-muted">Méthode de paiement : <strong>{{ ucfirst($order->payment_method) }}</strong></p>
                                        <p>Les détails de paiement seront ajoutés ici selon la méthode choisie.</p>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left"></i> Retour à mes commandes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Résumé de la commande</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-3">Articles commandés ({{ $order->lines->count() }})</h6>

                                @foreach($order->lines as $line)
                                    <div class="d-flex mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 small">{{ $line->description }}</h6>
                                            <p class="mb-0 small text-muted">Qté: {{ $line->quantity }}</p>
                                        </div>
                                        <div class="text-end">
                                            <span class="small">${{ number_format($line->total / 100, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Sous-total</span>
                                        <span>${{ number_format($order->sub_total / 100, 2) }}</span>
                                    </div>
                                    @if($order->shipping_total > 0)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Livraison</span>
                                            <span>${{ number_format($order->shipping_total / 100, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($order->discount_total > 0)
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Réduction</span>
                                            <span>-${{ number_format($order->discount_total / 100, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($order->tax_total > 0)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Taxe</span>
                                            <span>${{ number_format($order->tax_total / 100, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between border-top pt-3 mt-3">
                                        <strong>Total</strong>
                                        <strong class="text-primary fs-5">${{ number_format($order->total / 100, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Help -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h6 class="mb-3">Besoin d'aide ?</h6>
                                <p class="small text-muted mb-3">Si vous avez des questions concernant votre commande ou le paiement, n'hésitez pas à nous contacter.</p>
                                <a href="{{ route('public.company.contact') }}" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-envelope"></i> Nous contacter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
