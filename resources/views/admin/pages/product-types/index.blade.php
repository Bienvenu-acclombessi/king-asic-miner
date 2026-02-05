@extends('admin.components.app')
@section('title', 'Product Types Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-tags me-2"></i>Product Types</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product Types</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductTypeModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Product Type
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
                    <form action="{{ route('admin.product-types.index') }}" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.product-types.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Types Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Product Types List</h5>
                        <span class="badge bg-primary">{{ $productTypes->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Products Count</th>
                                    <th>Created</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productTypes as $productType)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-dark">#{{ $productType->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ is_array($productType->name) ? ($productType->name['en'] ?? 'N/A') : $productType->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-dark">
                                                <i class="bi bi-box me-1"></i>{{ $productType->products_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $productType->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editProductType({{ $productType->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteProductType({{ $productType->id }}, '{{ addslashes(is_array($productType->name) ? ($productType->name['en'] ?? 'N/A') : $productType->name) }}')"
                                                        data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                            </div>
                                            <h5 class="text-muted">No product types found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first product type</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductTypeModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Product Type
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($productTypes->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $productTypes->firstItem() }} to {{ $productTypes->lastItem() }} of {{ $productTypes->total() }} entries
                            </div>
                            <div>
                                {{ $productTypes->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.product-types.partials.create-modal')
@include('admin.pages.product-types.partials.edit-modal')
@include('admin.pages.product-types.partials.delete-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Edit Product Type Function
    function editProductType(productTypeId) {
        fetch(`/admin/product-types/${productTypeId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const productType = data.productType;

                    document.getElementById('edit_product_type_id').value = productType.id;
                    document.getElementById('edit_name').value = productType.name.en || '';

                    document.getElementById('editProductTypeForm').action = `/admin/product-types/${productType.id}`;

                    const editModal = new bootstrap.Modal(document.getElementById('editProductTypeModal'));
                    editModal.show();
                } else {
                    alert('Error loading product type data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading product type data');
            });
    }

    // Delete Product Type Function
    function deleteProductType(productTypeId, productTypeName) {
        document.getElementById('delete_product_type_name').textContent = productTypeName;
        document.getElementById('deleteProductTypeForm').action = `/admin/product-types/${productTypeId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductTypeModal'));
        deleteModal.show();
    }
</script>
@endsection
