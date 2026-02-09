@extends('admin.components.app')
@section('title', 'Edit Order')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div>
                <h2>Edit Order #{{ $order->reference }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.show', $order) }}">Order #{{ $order->reference }}</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Update Order Status</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label">Order Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                        Processing
                                    </option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                        Shipped
                                    </option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Current status: <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                </small>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                <select name="payment_status" id="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                        Paid
                                    </option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>
                                        Failed
                                    </option>
                                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>
                                        Refunded
                                    </option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Current status: <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                </small>
                            </div>

                            <div class="col-12 mb-4">
                                <label for="notes" class="form-label">Order Notes</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="5">{{ old('notes', $order->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Add any internal notes about this order</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Order
                            </button>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Order Reference:</th>
                            <td class="text-end">#{{ $order->reference }}</td>
                        </tr>
                        <tr>
                            <th>Customer:</th>
                            <td class="text-end">{{ $order->user->name ?? 'Guest' }}</td>
                        </tr>
                        <tr>
                            <th>Total Amount:</th>
                            <td class="text-end fw-bold">${{ number_format($order->total / 100, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Items:</th>
                            <td class="text-end">{{ $order->lines->count() }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td class="text-end">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Status Information -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Status Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">Status Guidelines</h6>
                        <ul class="mb-0 small ps-3">
                            <li><strong>Pending:</strong> Order received, awaiting processing</li>
                            <li><strong>Processing:</strong> Order is being prepared</li>
                            <li><strong>Shipped:</strong> Order has been shipped</li>
                            <li><strong>Completed:</strong> Order delivered successfully</li>
                            <li><strong>Cancelled:</strong> Order has been cancelled</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
