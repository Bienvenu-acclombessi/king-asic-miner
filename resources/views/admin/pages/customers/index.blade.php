@extends('admin.components.app')
@section('title', 'Customers')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Customers</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Customers</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Add New Customer</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="verified" class="form-select">
                                <option value="">All Status</option>
                                <option value="verified" {{ request('verified') === 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="unverified" {{ request('verified') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
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

                    @if($errors->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-centered table-hover text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Orders</th>
                                    <th>Reviews</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md">
                                                    <div class="avatar-title bg-light-primary text-primary rounded-circle">
                                                        {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="ms-3">
                                                    <h5 class="mb-0">
                                                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-primary-hover">
                                                            {{ $customer->name }}
                                                        </a>
                                                    </h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->telephone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-light-primary text-dark-primary">
                                                {{ $customer->orders_count }} orders
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-warning text-dark-warning">
                                                {{ $customer->reviews_count }} reviews
                                            </span>
                                        </td>
                                        <td>
                                            @if($customer->email_verified_at)
                                                <span class="badge bg-light-success text-dark-success">
                                                    <i class="bi bi-check-circle me-1"></i>Verified
                                                </span>
                                            @else
                                                <span class="badge bg-light-danger text-dark-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Unverified
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="text-reset" href="#" role="button" data-bs-toggle="dropdown">
                                                    <i class="feather-icon icon-more-vertical fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.customers.show', $customer) }}">
                                                            <i class="bi bi-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.customers.edit', $customer) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                                                              onsubmit="return confirm('Are you sure you want to delete this customer?')">
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
                                        <td colspan="8" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                            </div>
                                            <h5>No customers found</h5>
                                            <p class="text-muted">Customers will appear here once they sign up</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($customers->hasPages())
                        <div class="mt-4">
                            {{ $customers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
