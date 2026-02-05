@extends('admin.components.app')
@section('title', 'Brands Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-shop me-2"></i>Brands</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Brands</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Brand
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Oops! There were some problems:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.brands.index') }}" method="GET" class="row g-3">
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control" placeholder="Search by brand name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Brands Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Brands List</h5>
                        <span class="badge bg-primary">{{ $brands->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Website</th>
                                    <th>Products</th>
                                    <th>Created</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brands as $brand)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-dark">#{{ $brand->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $brand->name }}</h6>
                                                    @if(isset($brand->attribute_data['description']))
                                                        <small class="text-muted">{{ Str::limit($brand->attribute_data['description'], 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($brand->attribute_data['website']))
                                                <a href="{{ $brand->attribute_data['website'] }}" target="_blank" class="text-primary">
                                                    <i class="bi bi-link-45deg"></i> Website
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-dark">
                                                <i class="bi bi-box me-1"></i>{{ $brand->products_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $brand->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editBrand({{ $brand->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteBrand({{ $brand->id }}, '{{ addslashes($brand->name) }}')"
                                                        data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                            </div>
                                            <h5 class="text-muted">No brands found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first brand</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Brand
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($brands->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }} of {{ $brands->total() }} entries
                            </div>
                            <div>
                                {{ $brands->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.brands.partials.create-modal')
@include('admin.pages.brands.partials.edit-modal')
@include('admin.pages.brands.partials.delete-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Edit Brand Function
    function editBrand(brandId) {
        // Fetch brand data via AJAX
        fetch(`/admin/brands/${brandId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const brand = data.brand;

                    // Populate form fields
                    document.getElementById('edit_brand_id').value = brand.id;
                    document.getElementById('edit_name').value = brand.name || '';
                    document.getElementById('edit_description').value = brand.attribute_data.description || '';
                    document.getElementById('edit_website').value = brand.attribute_data.website || '';

                    // Update form action
                    document.getElementById('editBrandForm').action = `/admin/brands/${brand.id}`;

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editBrandModal'));
                    editModal.show();
                } else {
                    alert('Error loading brand data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading brand data');
            });
    }

    // Delete Brand Function
    function deleteBrand(brandId, brandName) {
        document.getElementById('delete_brand_name').textContent = brandName;
        document.getElementById('deleteBrandForm').action = `/admin/brands/${brandId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteBrandModal'));
        deleteModal.show();
    }
</script>
@endsection
