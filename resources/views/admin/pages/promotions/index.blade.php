@extends('admin.components.app')
@section('title', 'Promotions')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Promotions</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Promotions</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">Add New Promotion</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.promotions.index') }}" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Search promotions..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotions Table -->
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

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-centered table-hover text-nowrap">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Products</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promotion)
                                    <tr>
                                        <td>{{ $promotion->id }}</td>
                                        <td>
                                            <h5 class="mb-0 text-primary-hover">
                                                <a href="{{ route('admin.promotions.show', $promotion) }}">
                                                    {{ $promotion->title }}
                                                </a>
                                            </h5>
                                            @if($promotion->description)
                                                <small class="text-muted">{{ Str::limit($promotion->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($promotion->start_at || $promotion->end_at)
                                                <small>
                                                    @if($promotion->start_at)
                                                        <strong>From:</strong> {{ $promotion->start_at->format('M d, Y') }}<br>
                                                    @endif
                                                    @if($promotion->end_at)
                                                        <strong>To:</strong> {{ $promotion->end_at->format('M d, Y') }}
                                                    @endif
                                                </small>
                                            @else
                                                <span class="text-muted">No duration set</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($promotion->isActive())
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light-info text-dark-info">
                                                {{ $promotion->products()->count() }} products
                                            </span>
                                        </td>
                                        <td>{{ $promotion->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="text-reset" href="#" role="button" data-bs-toggle="dropdown">
                                                    <i class="feather-icon icon-more-vertical fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.promotions.show', $promotion) }}">
                                                            <i class="bi bi-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.promotions.edit', $promotion) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this promotion?')">
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
                                                <i class="bi bi-megaphone fs-1 text-muted"></i>
                                            </div>
                                            <h5>No promotions found</h5>
                                            <p class="text-muted">Get started by creating your first promotion</p>
                                            <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">Add Promotion</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($promotions->hasPages())
                        <div class="mt-4">
                            {{ $promotions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
