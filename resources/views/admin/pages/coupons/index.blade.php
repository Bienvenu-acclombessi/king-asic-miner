@extends('admin.components.app')
@section('title', 'Coupons')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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

    /* Fix Select2 dropdown z-index in modals */
    .select2-container {
        z-index: 10000 !important;
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
                    <h2>Coupons</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Coupons</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCouponModal">
                        <i class="bi bi-plus-circle me-2"></i>Create Coupon
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
                    <form action="{{ route('admin.coupons.index') }}" method="GET" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" placeholder="Search coupons..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
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

    <!-- Coupons Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Discount</th>
                                    <th>Usage</th>
                                    <th>Valid Period</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $coupon->coupon }}</strong>
                                        </td>
                                        <td>{{ $coupon->name }}</td>
                                        <td>
                                            <span class="badge bg-light-info text-dark-info">
                                                {{ ucfirst($coupon->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($coupon->type === 'percentage')
                                                {{ $coupon->discount_value }}%
                                            @elseif($coupon->type === 'fixed')
                                                ${{ number_format($coupon->discount_value, 2) }}
                                            @else
                                                Free Shipping
                                            @endif
                                        </td>
                                        <td>
                                            {{ $coupon->uses ?? 0 }} / {{ $coupon->max_uses ?? '∞' }}
                                        </td>
                                        <td>
                                            @if($coupon->starts_at || $coupon->ends_at)
                                                <small>
                                                    {{ $coupon->starts_at ? $coupon->starts_at->format('M d, Y') : 'No start' }}
                                                    -
                                                    {{ $coupon->ends_at ? $coupon->ends_at->format('M d, Y') : 'No end' }}
                                                </small>
                                            @else
                                                <span class="text-muted">No limit</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->isValid())
                                                <span class="badge bg-success">Active</span>
                                            @elseif($coupon->ends_at && $coupon->ends_at->lt(now()))
                                                <span class="badge bg-danger">Expired</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                            <!-- Debug info -->
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">
                                                DB: {{ $coupon->is_active ? 'true' : 'false' }}
                                                @if($coupon->starts_at && $coupon->starts_at->gt(now()))
                                                    | Not started
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-ghost-primary edit-coupon-btn"
                                                        data-id="{{ $coupon->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editCouponModal">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-ghost-danger delete-coupon-btn"
                                                        data-id="{{ $coupon->id }}"
                                                        data-code="{{ $coupon->coupon }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteCouponModal">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                            </div>
                                            <h5>No coupons found</h5>
                                            <p class="text-muted">Create your first coupon to get started</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCouponModal">
                                                Create Coupon
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($coupons->hasPages())
                        <div class="mt-4">
                            {{ $coupons->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.coupons.partials.create-modal')
@include('admin.pages.coupons.partials.edit-modal')
@include('admin.pages.coupons.partials.delete-modal')

@endsection

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Pass Laravel routes to JavaScript
    window.couponRoutes = {
        store: "{{ route('admin.coupons.store') }}",
        edit: "{{ route('admin.coupons.edit', ':id') }}",
        update: "{{ route('admin.coupons.update', ':id') }}",
        destroy: "{{ route('admin.coupons.destroy', ':id') }}",
        products: "{{ route('admin.coupons.data.products') }}",
        collections: "{{ route('admin.coupons.data.collections') }}",
        brands: "{{ route('admin.coupons.data.brands') }}",
    };

    // Initialize Select2
    function initSelect2() {
        // Products
        $('.select2-products').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select products...',
            allowClear: true,
            ajax: {
                url: window.couponRoutes.products,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        // Collections
        $('.select2-collections').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select collections...',
            allowClear: true,
            ajax: {
                url: window.couponRoutes.collections,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        // Brands
        $('.select2-brands').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select brands...',
            allowClear: true,
            ajax: {
                url: window.couponRoutes.brands,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });
    }

    // Fix modal z-index when opening
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 on page load
        initSelect2();

        const modals = document.querySelectorAll('.modal');

        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                // Force z-index on modal
                this.style.zIndex = 9999;

                // Force z-index on backdrop after a short delay
                setTimeout(() => {
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.style.zIndex = 9998;
                    }
                }, 10);

                // Reinitialize Select2 when modal is shown
                setTimeout(() => {
                    initSelect2();
                }, 100);
            });
        });
    });
</script>
<script src="{{ asset('admin/js/coupons.js') }}"></script>
@endpush
