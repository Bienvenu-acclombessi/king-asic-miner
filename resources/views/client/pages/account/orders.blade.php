@extends('client.pages.account.layouts.base')

@section('page_title') Mes Commandes @endsection

@section('content')
    <div class="py-6 p-md-6 p-lg-10">
        <!-- heading -->
        <h2 class="mb-6">Mes Commandes</h2>

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

        @if($orders->isEmpty())
            <div class="text-center py-10">
                <i class="bi bi-bag-x" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="mt-4">Aucune commande pour le moment</h4>
                <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
                <a href="{{ route('public.shop.index') }}" class="btn btn-primary mt-3">Commencer vos achats</a>
            </div>
        @else
            <div class="table-responsive-xxl border-0">
                <!-- Table -->
                <table class="table mb-0 text-nowrap table-centered">
                    <!-- Table Head -->
                    <thead class="bg-light">
                        <tr>
                            <th>Commande</th>
                            <th>Date</th>
                            <th>Articles</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
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
                            <tr>
                                <td class="align-middle">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="text-inherit fw-bold">
                                        #{{ $order->reference }}
                                    </a>
                                </td>
                                <td class="align-middle">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                                <td class="align-middle">
                                    {{ $order->lines->count() }} article(s)
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-{{ $paymentStatusColors[$order->payment_status] ?? 'secondary' }}">
                                        {{ $paymentStatusLabels[$order->payment_status] ?? $order->payment_status }}
                                    </span>
                                </td>
                                <td class="align-middle fw-bold">
                                    ${{ number_format($order->total / 100, 2) }}
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('customer.orders.show', $order->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($order->payment_status === 'pending' && $order->payment_method === 'coinpal')
                                            <a href="{{ route('public.checkout.payment', ['order' => $order->id]) }}"
                                               class="btn btn-sm btn-success"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="Payer maintenant">
                                                <i class="bi bi-credit-card"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection