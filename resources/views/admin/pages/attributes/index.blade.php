@extends('admin.components.app')
@section('title', 'Product Attributes')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Product Attributes</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attributes</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">Add New Attribute</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.attributes.index') }}" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control" placeholder="Search attributes..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Attributes Table -->
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
                                    <th>Attribute Name</th>
                                    <th>Values</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attributes as $attribute)
                                    <tr>
                                        <td>{{ $attribute->id }}</td>
                                        <td>
                                            <strong>{{ $attribute->name['en'] ?? $attribute->name[array_key_first($attribute->name)] ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @if(isset($attribute->configuration['options']) && is_array($attribute->configuration['options']) && count($attribute->configuration['options']) > 0)
                                                    @foreach($attribute->configuration['options'] as $option)
                                                        <span class="badge bg-light-primary text-dark-primary">
                                                            {{ $option }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted small">No values</span>
                                                @endif
                                            </div>
                                            @if(isset($attribute->configuration['options']) && is_array($attribute->configuration['options']))
                                                <small class="text-muted">{{ count($attribute->configuration['options']) }} value(s)</small>
                                            @else
                                                <small class="text-muted">0 value(s)</small>
                                            @endif
                                        </td>
                                        <td>{{ $attribute->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="text-reset" href="#" role="button" data-bs-toggle="dropdown">
                                                    <i class="feather-icon icon-more-vertical fs-5"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.attributes.edit', $attribute) }}">
                                                            <i class="bi bi-pencil-square me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this attribute?')">
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
                                        <td colspan="5" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-sliders fs-1 text-muted"></i>
                                            </div>
                                            <h5>No attributes found</h5>
                                            <p class="text-muted">Get started by creating your first product attribute</p>
                                            <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">Add Attribute</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($attributes->hasPages())
                        <div class="mt-4">
                            {{ $attributes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
