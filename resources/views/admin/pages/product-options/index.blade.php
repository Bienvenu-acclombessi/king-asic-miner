@extends('admin.components.app')
@section('title', 'Product Options Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-sliders me-2"></i>Product Options</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product Options</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductOptionModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Product Option
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
                    <form action="{{ route('admin.product-options.index') }}" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or handle..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="shared" class="form-select">
                                <option value="">All Options</option>
                                <option value="true" {{ request('shared') === 'true' ? 'selected' : '' }}>Shared Only</option>
                                <option value="false" {{ request('shared') === 'false' ? 'selected' : '' }}>Not Shared</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.product-options.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Options Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Product Options List</h5>
                        <span class="badge bg-primary">{{ $productOptions->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Label</th>
                                    <th>Handle</th>
                                    <th>Shared</th>
                                    <th>Products</th>
                                    <th>Values</th>
                                    <th>Created</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productOptions as $productOption)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-dark">#{{ $productOption->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ is_array($productOption->name) ? ($productOption->name['en'] ?? 'N/A') : $productOption->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($productOption->label)
                                                <span class="text-muted">{{ is_array($productOption->label) ? ($productOption->label['en'] ?? 'N/A') : $productOption->label }}</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $productOption->handle }}</code>
                                        </td>
                                        <td>
                                            @if($productOption->shared)
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Yes</span>
                                            @else
                                                <span class="badge bg-warning"><i class="bi bi-x-circle me-1"></i>No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-dark">
                                                <i class="bi bi-box me-1"></i>{{ $productOption->products_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-info text-dark">
                                                <i class="bi bi-list-check me-1"></i>{{ $productOption->values_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $productOption->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editProductOption({{ $productOption->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteProductOption({{ $productOption->id }}, '{{ addslashes(is_array($productOption->name) ? ($productOption->name['en'] ?? 'N/A') : $productOption->name) }}')"
                                                        data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                            </div>
                                            <h5 class="text-muted">No product options found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first product option</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductOptionModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Product Option
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($productOptions->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $productOptions->firstItem() }} to {{ $productOptions->lastItem() }} of {{ $productOptions->total() }} entries
                            </div>
                            <div>
                                {{ $productOptions->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.product-options.partials.create-modal')
@include('admin.pages.product-options.partials.edit-modal')
@include('admin.pages.product-options.partials.delete-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Edit Product Option Function
    function editProductOption(productOptionId) {
        fetch(`/admin/product-options/${productOptionId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const productOption = data.productOption;

                    document.getElementById('edit_product_option_id').value = productOption.id;
                    document.getElementById('edit_name').value = productOption.name.en || '';
                    document.getElementById('edit_label').value = productOption.label ? (productOption.label.en || '') : '';
                    document.getElementById('edit_handle').value = productOption.handle || '';
                    document.getElementById('edit_shared').checked = productOption.shared || false;

                    document.getElementById('editProductOptionForm').action = `/admin/product-options/${productOption.id}`;

                    const editModal = new bootstrap.Modal(document.getElementById('editProductOptionModal'));
                    editModal.show();
                } else {
                    alert('Error loading product option data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading product option data');
            });
    }

    // Delete Product Option Function
    function deleteProductOption(productOptionId, productOptionName) {
        document.getElementById('delete_product_option_name').textContent = productOptionName;
        document.getElementById('deleteProductOptionForm').action = `/admin/product-options/${productOptionId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductOptionModal'));
        deleteModal.show();
    }
</script>
@endsection
