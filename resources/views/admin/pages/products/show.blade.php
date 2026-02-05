@extends('admin.components.app')
@section('title', 'Product Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>{{ $product->name }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                            <li class="breadcrumb-item active">{{ $product->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">Edit Product</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Product Images -->
            @if($product->hasMedia('thumbnail') || $product->hasMedia('images'))
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Product Images</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if($product->hasMedia('thumbnail'))
                                <div class="col-md-4">
                                    <div class="border rounded p-2">
                                        <img src="{{ $product->getFirstMediaUrl('thumbnail') }}" alt="{{ $product->name }}" class="img-fluid rounded">
                                        <p class="text-center mb-0 mt-2"><small class="badge bg-primary">Main Image</small></p>
                                    </div>
                                </div>
                            @endif
                            @foreach($product->getMedia('images') as $media)
                                <div class="col-md-4">
                                    <div class="border rounded p-2">
                                        <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}" class="img-fluid rounded">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Product Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Product Information</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="200">SKU:</th>
                                <td>{{ $product->sku }}</td>
                            </tr>
                            <tr>
                                <th>Slug:</th>
                                <td>{{ $product->slug }}</td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-light-{{ $product->status->color ?? 'secondary' }} text-dark-{{ $product->status->color ?? 'secondary' }}">
                                        {{ $product->status->name ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Featured:</th>
                                <td>
                                    @if($product->is_featured)
                                        <span class="badge bg-light-success text-dark-success">Yes</span>
                                    @else
                                        <span class="badge bg-light-secondary text-dark-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @if($product->short_description)
                        <div class="mt-4">
                            <h5>Short Description</h5>
                            <p>{{ $product->short_description }}</p>
                        </div>
                    @endif

                    @if($product->description)
                        <div class="mt-4">
                            <h5>Description</h5>
                            <p>{{ $product->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Pricing</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Price</h6>
                            <h4>${{ number_format($product->price, 2) }}</h4>
                        </div>
                        @if($product->compare_price)
                            <div class="col-md-4">
                                <h6>Compare at Price</h6>
                                <h4>${{ number_format($product->compare_price, 2) }}</h4>
                            </div>
                        @endif
                        @if($product->cost_price)
                            <div class="col-md-4">
                                <h6>Cost Price</h6>
                                <h4>${{ number_format($product->cost_price, 2) }}</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Inventory</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Stock Quantity</h6>
                            <h4>
                                @if($product->stock_quantity > 0)
                                    <span class="text-success">{{ $product->stock_quantity }}</span>
                                @else
                                    <span class="text-danger">Out of Stock</span>
                                @endif
                            </h4>
                        </div>
                        @if($product->low_stock_threshold)
                            <div class="col-md-6">
                                <h6>Low Stock Threshold</h6>
                                <h4>{{ $product->low_stock_threshold }}</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            @if($product->reviews->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Recent Reviews ({{ $product->reviews->count() }})</h4>
                    </div>
                    <div class="card-body">
                        @foreach($product->reviews->take(5) as $review)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
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
            <!-- Shipping -->
            @if($product->weight || $product->length || $product->width || $product->height)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Shipping</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                @if($product->weight)
                                    <tr>
                                        <th>Weight:</th>
                                        <td>{{ $product->weight }} kg</td>
                                    </tr>
                                @endif
                                @if($product->length)
                                    <tr>
                                        <th>Length:</th>
                                        <td>{{ $product->length }} cm</td>
                                    </tr>
                                @endif
                                @if($product->width)
                                    <tr>
                                        <th>Width:</th>
                                        <td>{{ $product->width }} cm</td>
                                    </tr>
                                @endif
                                @if($product->height)
                                    <tr>
                                        <th>Height:</th>
                                        <td>{{ $product->height }} cm</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- SEO -->
            @if($product->meta_title || $product->meta_description || $product->meta_keywords)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">SEO</h4>
                    </div>
                    <div class="card-body">
                        @if($product->meta_title)
                            <div class="mb-3">
                                <h6>Meta Title</h6>
                                <p class="text-muted">{{ $product->meta_title }}</p>
                            </div>
                        @endif
                        @if($product->meta_description)
                            <div class="mb-3">
                                <h6>Meta Description</h6>
                                <p class="text-muted">{{ $product->meta_description }}</p>
                            </div>
                        @endif
                        @if($product->meta_keywords)
                            <div class="mb-3">
                                <h6>Meta Keywords</h6>
                                <p class="text-muted">{{ $product->meta_keywords }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Timestamps -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Timestamps</h4>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated:</th>
                                <td>{{ $product->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
