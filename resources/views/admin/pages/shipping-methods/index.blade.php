@extends('admin.components.app')
@section('title', 'Shipping Methods')

@push('styles')
<style>
    /* Fix modal z-index above sidebar */
    .modal-backdrop.show {
        z-index: 9998 !important;
    }

    .modal.show {
        z-index: 9999 !important;
    }

    /* Allow modal content to scroll */
    .modal-dialog-scrollable .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Shipping Methods</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Shipping Methods</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createShippingMethodModal">
                        <i class="bi bi-plus-circle me-2"></i>Create Shipping Method
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.shipping-methods.index') }}" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" placeholder="Search shipping methods..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
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

    <!-- Success Message -->
    <div id="successAlert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
        <span id="successMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Shipping Methods Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Conditions</th>
                                    <th>Delivery Time</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shippingMethods as $method)
                                    <tr>
                                        <td>
                                            <strong>{{ $method->name }}</strong>
                                            @if($method->description)
                                                <br><small class="text-muted">{{ Str::limit($method->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($method->price_type === 'free')
                                                <span class="badge bg-success">FREE</span>
                                            @elseif($method->price_type === 'percentage')
                                                {{ $method->price }}%
                                            @else
                                                ${{ number_format($method->price, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($method->min_order_amount)
                                                <small>Min: ${{ number_format($method->min_order_amount, 2) }}</small><br>
                                            @endif
                                            @if($method->max_order_amount)
                                                <small>Max: ${{ number_format($method->max_order_amount, 2) }}</small><br>
                                            @endif
                                            @if($method->max_weight)
                                                <small>Max weight: {{ $method->max_weight }}kg</small>
                                            @endif
                                            @if(!$method->min_order_amount && !$method->max_order_amount && !$method->max_weight)
                                                <span class="text-muted">No limits</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($method->estimated_delivery)
                                                <small>{{ $method->estimated_delivery }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $method->display_order }}</span>
                                        </td>
                                        <td>
                                            @if($method->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-ghost-primary edit-shipping-method-btn"
                                                        data-id="{{ $method->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editShippingMethodModal">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-ghost-danger delete-shipping-method-btn"
                                                        data-id="{{ $method->id }}"
                                                        data-name="{{ $method->name }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteShippingMethodModal">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-truck fs-1 text-muted"></i>
                                            </div>
                                            <h5>No shipping methods found</h5>
                                            <p class="text-muted">Create your first shipping method to get started</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createShippingMethodModal">
                                                Create Shipping Method
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($shippingMethods->hasPages())
                        <div class="mt-4">
                            {{ $shippingMethods->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.shipping-methods.partials.create-modal')
@include('admin.pages.shipping-methods.partials.edit-modal')
@include('admin.pages.shipping-methods.partials.delete-modal')

@endsection

@push('scripts')
<script>
    // Pass Laravel routes to JavaScript
    window.shippingMethodRoutes = {
        store: "{{ route('admin.shipping-methods.store') }}",
        edit: "{{ route('admin.shipping-methods.edit', ':id') }}",
        update: "{{ route('admin.shipping-methods.update', ':id') }}",
        destroy: "{{ route('admin.shipping-methods.destroy', ':id') }}",
    };
</script>
<script src="{{ asset('admin/js/shipping-methods.js') }}"></script>
@endpush
