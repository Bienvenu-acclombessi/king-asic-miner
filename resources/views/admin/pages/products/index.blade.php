@extends('admin.components.app')
@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Products</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Products</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.products.wizard') }}" class="btn btn-primary me-2">
                        <i class="bi bi-magic"></i> Create Product (Wizard)
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-plus-circle"></i> Create Product (Classic)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
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

    <!-- Products Table -->
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
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            @php
                                                $images = $product->attribute_data['images'] ?? null;
                                                $thumbnailPath = $images['thumbnail'] ?? null;
                                                $thumbnailUrl = $thumbnailPath ? asset('storage/' . $thumbnailPath) : null;
                                            @endphp
                                            @if($thumbnailUrl)
                                                <img src="{{ $thumbnailUrl }}" alt="{{ $product->attribute_data['name']['en'] ?? 'Product' }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="icon-shape icon-md bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $productName = $product->attribute_data['name']['en'] ?? $product->attribute_data['name'] ?? 'N/A';
                                                $firstVariant = $product->variants->first();
                                                $sku = $firstVariant ? $firstVariant->sku : 'N/A';
                                                $stock = $firstVariant ? $firstVariant->stock : 0;
                                                $firstPrice = $firstVariant ? $firstVariant->prices->first() : null;
                                                $price = $firstPrice ? $firstPrice->price / 100 : 0;
                                                $firstCollection = $product->collections->first();
                                                $categoryName = $firstCollection ? ($firstCollection->name ?? $firstCollection->attribute_data['name'] ?? 'N/A') : 'N/A';

                                                $statusColors = [
                                                    'draft' => 'secondary',
                                                    'published' => 'success',
                                                    'archived' => 'warning',
                                                    'out_of_stock' => 'danger'
                                                ];
                                                $statusColor = $statusColors[$product->status] ?? 'secondary';
                                            @endphp
                                            <div>
                                                <h5 class="mb-0 text-primary-hover">
                                                    <a href="{{ route('admin.products.edit', $product) }}">{{ $productName }}</a>
                                                </h5>
                                                <small class="text-muted">SKU: {{ $sku }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $categoryName }}</td>
                                        <td>
                                            @if($stock > 0)
                                                <span class="badge bg-success">{{ $stock }}</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $statusColor }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="text-reset" href="#" role="button" data-bs-toggle="dropdown">
                                                    <i class="feather-icon icon-more-vertical fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
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
                                            <h5>No products found</h5>
                                            <p class="text-muted">Get started by adding your first product</p>
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
