@extends('admin.components.app')
@section('title', 'Orders')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div>
                <h2>Orders</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                @foreach($statuses as $status)
                                    <option value="status_id" {{ request('status') == 'status_id' ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary">Filter</button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-centered table-hover text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-primary fw-bold">
                                                #{{ $order->order_number ?? $order->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $order->user->name ?? 'Guest' }}</h6>
                                                <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $order->items->count() }} items</td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            @if($order->orderStatus)
                                                <span class="badge bg-light-primary text-dark-primary">
                                                    {{ $order->orderStatus->name }}
                                                </span>
                                            @elseif($order->status)
                                                <span class="badge bg-light-secondary text-dark-secondary">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            @else
                                                <span class="badge bg-light-secondary text-dark-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="text-reset" href="#" role="button" data-bs-toggle="dropdown">
                                                    <i class="feather-icon icon-more-vertical fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}">
                                                            <i class="bi bi-eye me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.orders.edit', $order) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                                              onsubmit="return confirm('Are you sure you want to delete this order?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                            </div>
                                            <h5>No orders found</h5>
                                            <p class="text-muted">Orders from customers will appear here</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
