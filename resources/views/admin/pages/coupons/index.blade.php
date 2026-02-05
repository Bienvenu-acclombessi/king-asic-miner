@extends('admin.components.app')
@section('title', 'Coupons')

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
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Create Coupon</a>
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

    <!-- Coupons Table -->
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
                                    <th>Code</th>
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
                                            <strong class="text-primary">{{ $coupon->code }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-info text-dark-info">
                                                {{ ucfirst($coupon->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($coupon->type === 'percentage')
                                                {{ $coupon->discount }}%
                                            @else
                                                ${{ number_format($coupon->discount, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $coupon->used_count ?? 0 }} / {{ $coupon->usage_limit ?? '∞' }}
                                        </td>
                                        <td>
                                            @if($coupon->valid_from || $coupon->valid_to)
                                                <small>
                                                    {{ $coupon->valid_from ? $coupon->valid_from->format('M d, Y') : 'No start' }}
                                                    -
                                                    {{ $coupon->valid_to ? $coupon->valid_to->format('M d, Y') : 'No end' }}
                                                </small>
                                            @else
                                                <span class="text-muted">No limit</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->isValid())
                                                <span class="badge bg-light-success text-dark-success">Active</span>
                                            @elseif($coupon->valid_to && $coupon->valid_to->lt(now()))
                                                <span class="badge bg-light-danger text-dark-danger">Expired</span>
                                            @else
                                                <span class="badge bg-light-secondary text-dark-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="text-reset" href="#" role="button" data-bs-toggle="dropdown">
                                                    <i class="feather-icon icon-more-vertical fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.coupons.edit', $coupon) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                                              onsubmit="return confirm('Are you sure you want to delete this coupon?')">
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
                                            <h5>No coupons found</h5>
                                            <p class="text-muted">Create your first coupon to get started</p>
                                            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Create Coupon</a>
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
@endsection
