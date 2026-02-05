@extends('admin.components.app')
@section('title', 'Promotion Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Promotion Details</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Promotions</a></li>
                            <li class="breadcrumb-item active">{{ $promotion->title }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-primary">Edit Promotion</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Promotion Info -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Promotion Information</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ $promotion->title }}</h5>
                        @if($promotion->description)
                            <p class="text-muted mb-0">{{ $promotion->description }}</p>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Start Date:</strong>
                            <p class="text-muted">
                                {{ $promotion->start_at ? $promotion->start_at->format('F d, Y h:i A') : 'Not set' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>End Date:</strong>
                            <p class="text-muted">
                                {{ $promotion->end_at ? $promotion->end_at->format('F d, Y h:i A') : 'No expiration' }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        @if($promotion->isActive())
                            <span class="badge bg-success ms-2">Active</span>
                        @else
                            <span class="badge bg-secondary ms-2">Inactive</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Created:</strong>
                        <p class="text-muted">{{ $promotion->created_at->format('F d, Y h:i A') }}</p>
                    </div>

                    <div>
                        <strong>Last Updated:</strong>
                        <p class="text-muted">{{ $promotion->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Associated Products -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Associated Products ({{ $promotion->products()->count() }})</h4>
                </div>
                <div class="card-body">
                    @if($promotion->products()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promotion->products as $product)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.products.show', $product) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td>{{ $product->sku }}</td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $product->status == 'published' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No products associated with this promotion yet</p>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">Browse Products</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-2"></i>Edit Promotion
                        </a>
                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this promotion?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Delete Promotion
                            </button>
                        </form>
                        <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
