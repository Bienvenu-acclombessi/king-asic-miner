@extends('admin.components.app')
@section('title', 'Product Slides Management')

@push('styles')
<style>
/* Fix modal and backdrop z-index to appear above sidebar */
.modal-backdrop.show {
    z-index: 9998 !important;
}
.modal.show {
    z-index: 9999 !important;
}
.modal-dialog {
    z-index: 9999 !important;
}
/* Ensure modal body can scroll */
.modal-dialog-scrollable .modal-body {
    overflow-y: auto !important;
    max-height: calc(100vh - 200px) !important;
}
.slide-thumbnail {
    width: 80px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-image me-2"></i>Product Slides</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product Slides</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductSlideModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Slide
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
                    <form action="{{ route('admin.product-slides.index') }}" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Search by product name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Slides</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.product-slides.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Slides Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Product Slides List</h5>
                        <span class="badge bg-primary">{{ $productSlides->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Position</th>
                                    <th>Background</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productSlides as $slide)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-dark">#{{ $slide->id }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $slide->position }}</span>
                                        </td>
                                        <td>
                                            @if($slide->background_image)
                                                <img src="{{ asset('storage/' . $slide->background_image) }}" alt="Background" class="slide-thumbnail">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($slide->product)
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            @php
                                                                $name = $slide->product->attribute_data['name'] ?? 'N/A';
                                                            @endphp
                                                            {{ is_array($name) ? ($name['en'] ?? $name[0] ?? 'N/A') : $name }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($slide->is_active)
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                                            @else
                                                <span class="badge bg-warning"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $slide->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editProductSlide({{ $slide->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteProductSlide({{ $slide->id }}, '{{ addslashes(is_array($name ?? '') ? ($name['en'] ?? 'N/A') : ($name ?? 'N/A')) }}')"
                                                        data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox display-4 text-muted"></i>
                                            </div>
                                            <h5 class="text-muted">No product slides found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first product slide</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductSlideModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Product Slide
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($productSlides->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $productSlides->firstItem() }} to {{ $productSlides->lastItem() }} of {{ $productSlides->total() }} entries
                            </div>
                            <div>
                                {{ $productSlides->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.product-slides.partials.create-modal')
@include('admin.pages.product-slides.partials.edit-modal')
@include('admin.pages.product-slides.partials.delete-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Load products when create modal opens
    document.getElementById('createProductSlideModal').addEventListener('show.bs.modal', function () {
        fetch('/king-admin/product-slides/create')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const productSelect = document.getElementById('product_id');
                    productSelect.innerHTML = '<option value="">Select a product</option>';
                    data.products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.name;
                        productSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                alert('Error loading products. Please try again.');
            });
    });

    // Preview background image for create modal
    document.getElementById('background_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('background_preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded mt-2" style="max-height: 200px;">`;
            }
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });

    // Preview new background image for edit modal
    document.getElementById('edit_background_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('edit_background_preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="mt-2">
                        <strong>New Image Preview:</strong>
                        <img src="${e.target.result}" class="img-fluid rounded mt-2" style="max-height: 200px;">
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });

    // Edit Product Slide Function
    function editProductSlide(slideId) {
        fetch(`/king-admin/product-slides/${slideId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const slide = data.productSlide;
                    const products = data.products;

                    // Set basic info
                    document.getElementById('edit_product_slide_id').value = slide.id;
                    document.getElementById('edit_is_active').checked = slide.is_active;

                    // Populate product select
                    const productSelect = document.getElementById('edit_product_id');
                    productSelect.innerHTML = '<option value="">Select a product</option>';
                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.name;
                        if (product.id === slide.product_id) {
                            option.selected = true;
                        }
                        productSelect.appendChild(option);
                    });

                    // Show current background image
                    const currentImageDiv = document.getElementById('current_background_preview');
                    if (slide.background_image) {
                        currentImageDiv.innerHTML = `
                            <img src="/storage/${slide.background_image}" alt="Current Background" class="img-fluid rounded" style="max-height: 200px;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="edit_remove_background" name="remove_background" value="1">
                                <label class="form-check-label" for="edit_remove_background">
                                    Remove current background
                                </label>
                            </div>
                        `;
                    } else {
                        currentImageDiv.innerHTML = '<p class="text-muted">No background image</p>';
                    }

                    document.getElementById('editProductSlideForm').action = `/king-admin/product-slides/${slide.id}`;

                    const editModal = new bootstrap.Modal(document.getElementById('editProductSlideModal'));
                    editModal.show();
                } else {
                    alert('Error loading product slide data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading product slide data');
            });
    }

    // Delete Product Slide Function
    function deleteProductSlide(slideId, productName) {
        document.getElementById('delete_product_name').textContent = productName;
        document.getElementById('deleteProductSlideForm').action = `/king-admin/product-slides/${slideId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductSlideModal'));
        deleteModal.show();
    }
</script>
@endsection
