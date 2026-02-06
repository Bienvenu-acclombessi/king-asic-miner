@extends('client.pages.account.layouts.base')

@section('page_title') Détail de la commande @endsection

@section('content')
<div class="py-6 p-md-6 p-lg-10">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('customer.orders.index') }}">Mes commandes</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Commande #{{ $id ?? '14899' }}</li>
            </ol>
        </nav>
    </div>

    <!-- Order Header -->
    <div class="d-flex justify-content-between align-items-center mb-6">
        <div>
            <h2 class="mb-2">Commande #{{ $id ?? '14899' }}</h2>
            <p class="text-muted mb-0">Passée le March 5, 2023</p>
        </div>
        <div>
            <span class="badge bg-warning fs-6">En traitement</span>
        </div>
    </div>

    <!-- Order Status Timeline -->
    <div class="card mb-6">
        <div class="card-body p-6">
            <h4 class="mb-4">Suivi de la commande</h4>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div class="text-center">
                            <div class="mb-2">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                            </div>
                            <p class="mb-0 fw-semibold">Commandée</p>
                            <small class="text-muted">March 5, 2023</small>
                        </div>
                        <div class="text-center">
                            <div class="mb-2">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                            </div>
                            <p class="mb-0 fw-semibold">Confirmée</p>
                            <small class="text-muted">March 5, 2023</small>
                        </div>
                        <div class="text-center">
                            <div class="mb-2">
                                <i class="bi bi-circle text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <p class="mb-0 fw-semibold">Expédiée</p>
                            <small class="text-muted">En attente</small>
                        </div>
                        <div class="text-center">
                            <div class="mb-2">
                                <i class="bi bi-circle text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <p class="mb-0 fw-semibold">Livrée</p>
                            <small class="text-muted">En attente</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8">
            <div class="card mb-6">
                <div class="card-body p-6">
                    <h4 class="mb-4">Articles commandés</h4>

                    <!-- Item -->
                    <div class="d-flex mb-4 pb-4 border-bottom">
                        <div class="me-4">
                            <img src="/assets/kingshop/assets/images/products/product-img-1.jpg" alt="Product" class="img-fluid" style="width: 80px;">
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Antminer S19 Pro</h5>
                            <p class="text-muted mb-2">110 TH/s - 3250W</p>
                            <p class="mb-0">Quantité: 1</p>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">$2,500.00</h5>
                        </div>
                    </div>

                    <!-- Item -->
                    <div class="d-flex">
                        <div class="me-4">
                            <img src="/assets/kingshop/assets/images/products/product-img-2.jpg" alt="Product" class="img-fluid" style="width: 80px;">
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">WhatsMiner M30S++</h5>
                            <p class="text-muted mb-2">112 TH/s - 3400W</p>
                            <p class="mb-0">Quantité: 1</p>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">$2,800.00</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card">
                <div class="card-body p-6">
                    <h4 class="mb-4">Adresse de livraison</h4>
                    <p class="mb-0">
                        <strong>John Doe</strong><br>
                        123 Main Street<br>
                        Apartment 4B<br>
                        New York, NY 10001<br>
                        United States<br>
                        <strong>Tel:</strong> +1 234 567 8900
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body p-6">
                    <h4 class="mb-4">Résumé de la commande</h4>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Sous-total</span>
                        <span>$5,300.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Livraison</span>
                        <span>$50.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Taxe</span>
                        <span>$530.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong class="text-primary">$5,880.00</strong>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-light text-dark">Paiement: Carte de crédit</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body p-6">
                    <h5 class="mb-4">Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-download"></i> Télécharger la facture
                        </a>
                        <form action="{{ route('customer.orders.cancel', $id ?? '14899') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                <i class="bi bi-x-circle"></i> Annuler la commande
                            </button>
                        </form>
                        <a href="{{ route('public.company.contact') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-question-circle"></i> Contacter le support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
