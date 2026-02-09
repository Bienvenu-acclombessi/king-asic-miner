@extends('client.pages.account.layouts.base')

@section('page_title') Détail de la commande @endsection

@section('content')
<div class="py-6 p-md-6 p-lg-10">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('customer.orders.index') }}">Mes commandes</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Commande #{{ $order->reference }}</li>
            </ol>
        </nav>
    </div>

    <!-- Order Header -->
    <div class="d-flex justify-content-between align-items-center mb-6">
        <div>
            <h2 class="mb-2">Commande #{{ $order->reference }}</h2>
            <p class="text-muted mb-0">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div>
            @php
                $statusColors = [
                    'pending' => 'warning',
                    'processing' => 'info',
                    'shipped' => 'primary',
                    'completed' => 'success',
                    'cancelled' => 'danger'
                ];
                $statusLabels = [
                    'pending' => 'En attente',
                    'processing' => 'En traitement',
                    'shipped' => 'Expédiée',
                    'completed' => 'Complétée',
                    'cancelled' => 'Annulée'
                ];
            @endphp
            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                {{ $statusLabels[$order->status] ?? $order->status }}
            </span>
        </div>
    </div>

    <!-- Payment Alert -->
    @if($order->payment_status === 'pending' && $order->payment_method === 'coinpal')
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.5rem;"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-1">Paiement en attente</h5>
                <p class="mb-0">Votre commande est en attente de paiement. Veuillez finaliser votre paiement pour que nous puissions traiter votre commande.</p>
            </div>
            <a href="{{ route('public.checkout.payment', ['order' => $order->id]) }}" class="btn btn-warning ms-3">
                <i class="bi bi-credit-card"></i> Payer maintenant
            </a>
        </div>
    @elseif($order->payment_status === 'pending' && $order->payment_method === 'bank_transfer')
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill me-3" style="font-size: 1.5rem;"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-1">En attente de virement bancaire</h5>
                <p class="mb-0">Nous sommes en attente de la réception de votre virement bancaire. Votre commande sera traitée dès réception du paiement.</p>
            </div>
        </div>
    @endif

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
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div class="text-center">
                            <div class="mb-2">
                                <i class="bi {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}" style="font-size: 2rem;"></i>
                            </div>
                            <p class="mb-0 fw-semibold">En traitement</p>
                            <small class="text-muted">{{ in_array($order->status, ['processing', 'shipped', 'completed']) ? $order->updated_at->format('d/m/Y') : 'En attente' }}</small>
                        </div>
                        <div class="text-center">
                            <div class="mb-2">
                                <i class="bi {{ in_array($order->status, ['shipped', 'completed']) ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}" style="font-size: 2rem;"></i>
                            </div>
                            <p class="mb-0 fw-semibold">Expédiée</p>
                            <small class="text-muted">{{ in_array($order->status, ['shipped', 'completed']) ? $order->updated_at->format('d/m/Y') : 'En attente' }}</small>
                        </div>
                        <div class="text-center">
                            <div class="mb-2">
                                <i class="bi {{ $order->status === 'completed' ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}" style="font-size: 2rem;"></i>
                            </div>
                            <p class="mb-0 fw-semibold">Livrée</p>
                            <small class="text-muted">{{ $order->status === 'completed' ? $order->updated_at->format('d/m/Y') : 'En attente' }}</small>
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

                    @foreach($order->lines as $index => $line)
                        <div class="d-flex {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                            <div class="me-4">
                                @php
                                    $image = null;
                                    if ($line->purchasable) {
                                        if ($line->purchasable->images && $line->purchasable->images->count() > 0) {
                                            $image = $line->purchasable->images->first();
                                        } elseif ($line->purchasable->thumbnail) {
                                            $image = $line->purchasable->thumbnail;
                                        }
                                    }
                                @endphp
                                @if($image)
                                    @if(is_object($image))
                                        <img src="{{ $image->url }}" alt="{{ $line->description }}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $line->description }}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    @endif
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 80px;">
                                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $line->description }}</h5>
                                @if($line->option)
                                    <p class="text-muted mb-2 small">{{ $line->option }}</p>
                                @endif
                                <p class="mb-0">Quantité: <strong>{{ $line->quantity }}</strong></p>
                                <p class="mb-0 small text-muted">Prix unitaire: ${{ number_format($line->unit_price / 100, 2) }}</p>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0">${{ number_format($line->total / 100, 2) }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address -->
            @php
                $shippingAddress = $order->addresses->where('type', 'shipping')->first();
            @endphp
            @if($shippingAddress)
                <div class="card">
                    <div class="card-body p-6">
                        <h4 class="mb-4">Adresse de livraison</h4>
                        <p class="mb-0">
                            <strong>{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</strong><br>
                            @if($shippingAddress->company_name)
                                {{ $shippingAddress->company_name }}<br>
                            @endif
                            {{ $shippingAddress->line_one }}<br>
                            @if($shippingAddress->line_two)
                                {{ $shippingAddress->line_two }}<br>
                            @endif
                            {{ $shippingAddress->city }}, {{ $shippingAddress->postcode }}<br>
                            {{ $shippingAddress->country->name ?? '' }}<br>
                            <strong>Tel:</strong> {{ $shippingAddress->contact_phone }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body p-6">
                    <h4 class="mb-4">Résumé de la commande</h4>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Sous-total</span>
                        <span>${{ number_format($order->sub_total / 100, 2) }}</span>
                    </div>
                    @if($order->shipping_total > 0)
                        <div class="d-flex justify-content-between mb-3">
                            <span>Livraison</span>
                            <span>${{ number_format($order->shipping_total / 100, 2) }}</span>
                        </div>
                    @endif
                    @if($order->discount_total > 0)
                        <div class="d-flex justify-content-between mb-3 text-success">
                            <span>Réduction</span>
                            <span>-${{ number_format($order->discount_total / 100, 2) }}</span>
                        </div>
                    @endif
                    @if($order->tax_total > 0)
                        <div class="d-flex justify-content-between mb-3">
                            <span>Taxe</span>
                            <span>${{ number_format($order->tax_total / 100, 2) }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong class="text-primary">${{ number_format($order->total / 100, 2) }}</strong>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-light text-dark">
                            Paiement: {{ ucfirst($order->payment_method) }}
                        </span>
                    </div>
                    <div>
                        @php
                            $paymentStatusColors = [
                                'pending' => 'warning',
                                'paid' => 'success',
                                'failed' => 'danger',
                                'refunded' => 'info'
                            ];
                            $paymentStatusLabels = [
                                'pending' => 'En attente',
                                'paid' => 'Payée',
                                'failed' => 'Échouée',
                                'refunded' => 'Remboursée'
                            ];
                        @endphp
                        <span class="badge bg-{{ $paymentStatusColors[$order->payment_status] ?? 'secondary' }}">
                            Statut: {{ $paymentStatusLabels[$order->payment_status] ?? $order->payment_status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body p-6">
                    <h5 class="mb-4">Actions</h5>
                    <div class="d-grid gap-2">
                        @if($order->payment_status === 'pending' && $order->payment_method === 'coinpal')
                            <a href="{{ route('public.checkout.payment', ['order' => $order->id]) }}" class="btn btn-success">
                                <i class="bi bi-credit-card"></i> Finaliser le paiement
                            </a>
                        @endif

                        @if(!in_array($order->status, ['shipped', 'completed', 'cancelled']))
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                    <i class="bi bi-x-circle"></i> Annuler la commande
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('customer.orders.reorder', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-arrow-repeat"></i> Commander à nouveau
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
