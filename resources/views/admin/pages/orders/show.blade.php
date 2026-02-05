@extends('admin.components.app')
@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Order #{{ $order->order_number ?? $order->id }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                            <li class="breadcrumb-item active">Order #{{ $order->order_number ?? $order->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">Edit Order</a>
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
                <div class="card-header">
                    <h4 class="mb-0">Order Items</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="icon-shape icon-md me-3">
                                                @else
                                                    <div class="icon-shape icon-md bg-light me-3">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name ?? 'Product Deleted' }}</h6>
                                                    @if($item->product)
                                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</td>
                                </tr>
                                @if($order->tax_amount)
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                        <td class="text-end">${{ number_format($order->tax_amount, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->shipping_amount)
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                        <td class="text-end">${{ number_format($order->shipping_amount, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->discount_amount)
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                                        <td class="text-end text-danger">-${{ number_format($order->discount_amount, 2) }}</td>
                                    </tr>
                                @endif
                                <tr class="border-top">
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong class="fs-4">${{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Order Notes</h4>
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
                <div class="card-header">
                    <h4 class="mb-0">Customer</h4>
                </div>
                <div class="card-body">
                    @if($order->user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg">
                                <div class="avatar-title bg-light-primary text-primary rounded-circle">
                                    {{ substr($order->user->name, 0, 2) }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0">{{ $order->user->name }}</h5>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </div>
                        </div>
                        <a href="{{ route('admin.customers.show', $order->user) }}" class="btn btn-sm btn-outline-primary w-100">
                            View Customer
                        </a>
                    @else
                        <p class="text-muted mb-0">Guest Customer</p>
                    @endif
                </div>
            </div>

            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Order Status</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <select name="status_id" class="form-select" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}"
                                            {{ $order->status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="status" value="{{ $order->status }}">
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Payment Info -->
            @if($order->payment)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Payment</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th>Method:</th>
                                <td>{{ $order->payment->payment_method ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-light-success text-dark-success">
                                        {{ $order->payment->status ?? 'Paid' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Amount:</th>
                                <td>${{ number_format($order->payment->amount ?? $order->total_amount, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Order Info -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Order Information</h4>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Order Date:</th>
                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $order->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
