@extends('admin.components.app')
@section('title', 'Reviews')

@section('content')
<div class="container-fluid">
    <div class="row mb-8">
        <div class="col-md-12">
            <h2>Product Reviews</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reviews</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Search reviews..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="rating" class="form-select">
                                <option value="">All Ratings</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary">Filter</button>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Customer</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.products.show', $review->product) }}" class="text-primary">
                                                {{ $review->product->name }}
                                            </a>
                                        </td>
                                        <td>{{ $review->user->name ?? 'Guest' }}</td>
                                        <td>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-width: 300px;">
                                                {{ Str::limit($review->comment, 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($review->is_approved)
                                                <span class="badge bg-light-success text-dark-success">Approved</span>
                                            @else
                                                <span class="badge bg-light-warning text-dark-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="text-reset" href="#" role="button" data-bs-toggle="dropdown">
                                                    <i class="feather-icon icon-more-vertical fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    @if(!$review->is_approved)
                                                        <li>
                                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="bi bi-check-circle me-2"></i>Approve
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.reviews.edit', $review) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                                              onsubmit="return confirm('Are you sure?')">
                                                            @csrf @method('DELETE')
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
                                            <i class="bi bi-inbox fs-1 text-muted"></i>
                                            <h5 class="mt-3">No reviews found</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($reviews->hasPages())
                        <div class="mt-4">{{ $reviews->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
