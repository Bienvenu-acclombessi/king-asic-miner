@extends('admin.components.app')
@section('title', 'Customer Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>{{ $user->name }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
                            <li class="breadcrumb-item active">{{ $user->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.customers.edit', $user) }}" class="btn btn-primary">Edit Customer</a>
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
            <!-- Recent Orders -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Recent Orders ({{ $user->orders->count() }})</h4>
                </div>
                <div class="card-body">
                    @if($user->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders->take(10) as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-primary fw-bold">
                                                    #{{ $order->order_number ?? $order->id }}
                                                </a>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>{{ $order->items->count() }}</td>
                                            <td>${{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                @if($order->orderStatus)
                                                    <span class="badge bg-light-primary text-dark-primary">
                                                        {{ $order->orderStatus->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light-secondary text-dark-secondary">
                                                        {{ ucfirst($order->status ?? 'N/A') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-ghost-secondary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($user->orders->count() > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.orders.index', ['customer' => $user->id]) }}" class="btn btn-link">
                                    View all {{ $user->orders->count() }} orders
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No orders yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews -->
            @if($user->reviews->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Reviews ({{ $user->reviews->count() }})</h4>
                    </div>
                    <div class="card-body">
                        @foreach($user->reviews->take(5) as $review)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $review->product->name ?? 'Product Deleted' }}</strong>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="bi bi-star-fill"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                @if($review->comment)
                                    <p class="mt-2 mb-0">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Customer Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Customer Information</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-xl mx-auto mb-3">
                            <div class="avatar-title bg-light-primary text-primary rounded-circle fs-1">
                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                            </div>
                        </div>
                        <h5>{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                        @if($user->email_verified_at)
                            <span class="badge bg-light-success text-dark-success">
                                <i class="bi bi-check-circle me-1"></i>Verified
                            </span>
                        @else
                            <span class="badge bg-light-danger text-dark-danger">
                                <i class="bi bi-x-circle me-1"></i>Unverified
                            </span>
                        @endif
                    </div>

                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <th>First Name:</th>
                                <td>{{ $user->first_name }}</td>
                            </tr>
                            <tr>
                                <th>Last Name:</th>
                                <td>{{ $user->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $user->telephone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Member Since:</th>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Statistics</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Orders</span>
                            <strong>{{ $user->orders->count() }}</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Spent</span>
                            <strong>${{ number_format($user->orders->sum('total_amount'), 2) }}</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Reviews</span>
                            <strong>{{ $user->reviews->count() }}</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Wishlist Items</span>
                            <strong>{{ $user->wishlists->count() }}</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
