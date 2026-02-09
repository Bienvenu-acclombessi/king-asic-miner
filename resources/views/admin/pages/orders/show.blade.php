@extends('admin.components.app')
@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Order #{{ $order->reference }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                            <li class="breadcrumb-item active">Order #{{ $order->reference }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Edit Order
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Order Items</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="border-bottom">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->lines as $line)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
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
                                                        <img src="{{ $image->url }}" alt="{{ $line->description }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $line->description }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    @endif
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $line->description }}</h6>
                                                    @if($line->option)
                                                        <small class="text-muted">{{ $line->option }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($line->unit_price / 100, 2) }}</td>
                                        <td>{{ $line->quantity }}</td>
                                        <td class="text-end fw-bold">${{ number_format($line->total / 100, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($order->sub_total / 100, 2) }}</td>
                                </tr>
                                @if($order->discount_total > 0)
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                                        <td class="text-end text-success">-${{ number_format($order->discount_total / 100, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->shipping_total > 0)
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                        <td class="text-end">${{ number_format($order->shipping_total / 100, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->tax_total > 0)
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                        <td class="text-end">${{ number_format($order->tax_total / 100, 2) }}</td>
                                    </tr>
                                @endif
                                <tr class="border-top">
                                    <td colspan="3" class="text-end"><strong class="fs-5">Total:</strong></td>
                                    <td class="text-end"><strong class="fs-4 text-primary">${{ number_format($order->total / 100, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            @php
                $shippingAddress = $order->addresses->where('type', 'shipping')->first();
                $billingAddress = $order->addresses->where('type', 'billing')->first();
            @endphp

            <div class="row">
                @if($shippingAddress)
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Shipping Address</h5>
                            </div>
                            <div class="card-body">
                                <address class="mb-0">
                                    <strong>{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</strong><br>
                                    @if($shippingAddress->company_name)
                                        {{ $shippingAddress->company_name }}<br>
                                    @endif
                                    {{ $shippingAddress->line_one }}<br>
                                    @if($shippingAddress->line_two)
                                        {{ $shippingAddress->line_two }}<br>
                                    @endif
                                    {{ $shippingAddress->city }}, {{ $shippingAddress->postcode }}<br>
                                    {{ $shippingAddress->country->name ?? '' }}<br><br>
                                    <strong>Phone:</strong> {{ $shippingAddress->contact_phone }}<br>
                                    <strong>Email:</strong> {{ $shippingAddress->contact_email }}
                                </address>
                            </div>
                        </div>
                    </div>
                @endif

                @if($billingAddress)
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Billing Address</h5>
                            </div>
                            <div class="card-body">
                                <address class="mb-0">
                                    <strong>{{ $billingAddress->first_name }} {{ $billingAddress->last_name }}</strong><br>
                                    @if($billingAddress->company_name)
                                        {{ $billingAddress->company_name }}<br>
                                    @endif
                                    {{ $billingAddress->line_one }}<br>
                                    @if($billingAddress->line_two)
                                        {{ $billingAddress->line_two }}<br>
                                    @endif
                                    {{ $billingAddress->city }}, {{ $billingAddress->postcode }}<br>
                                    {{ $billingAddress->country->name ?? '' }}<br><br>
                                    <strong>Phone:</strong> {{ $billingAddress->contact_phone }}<br>
                                    <strong>Email:</strong> {{ $billingAddress->contact_email }}
                                </address>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Notes -->
            @if($order->notes)
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Customer Info -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    @if($order->user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 50px; height: 50px;">
                                {{ strtoupper(substr($order->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $order->user->name }}</h6>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-person"></i> Guest Customer
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'processing' => 'info',
                            'shipped' => 'primary',
                            'completed' => 'success',
                            'cancelled' => 'danger'
                        ];
                    @endphp
                    <div class="mb-3">
                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-pencil"></i> Update Status
                    </a>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-3">
                        <tr>
                            <th>Method:</th>
                            <td class="text-end">{{ ucfirst($order->payment_method) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td class="text-end">
                                @php
                                    $paymentStatusColors = [
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'failed' => 'danger',
                                        'refunded' => 'info'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $paymentStatusColors[$order->payment_status] ?? 'secondary' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td class="text-end fw-bold">${{ number_format($order->total / 100, 2) }}</td>
                        </tr>
                    </table>

                    @if($order->transactions->isNotEmpty())
                        <hr>
                        <h6 class="mb-2">Transactions</h6>
                        @foreach($order->transactions as $transaction)
                            <div class="small mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $transaction->type }}</span>
                                    <span class="badge bg-{{ $transaction->success ? 'success' : 'danger' }}">
                                        {{ $transaction->status }}
                                    </span>
                                </div>
                                <div class="text-muted">
                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Timeline</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Created:</th>
                            <td class="text-end">{{ $order->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td class="text-end">{{ $order->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @if($order->placed_at)
                            <tr>
                                <th>Placed At:</th>
                                <td class="text-end">{{ $order->placed_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
