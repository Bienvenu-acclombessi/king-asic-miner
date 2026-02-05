@extends('admin.components.app')
@section('title', 'Categories Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-folder2-open me-2"></i>Product Categories</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Categories</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Category
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
                    <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="collection_group" class="form-select">
                                <option value="">All Collection Groups</option>
                                @foreach($collectionGroups as $group)
                                    <option value="{{ $group->id }}" {{ request('collection_group') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="static" {{ request('type') == 'static' ? 'selected' : '' }}>Static</option>
                                <option value="dynamic" {{ request('type') == 'dynamic' ? 'selected' : '' }}>Dynamic</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Categories List</h5>
                        <span class="badge bg-primary">{{ $categories->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Collection Group</th>
                                    <th>Parent</th>
                                    <th>Type</th>
                                    <th>Products</th>
                                    <th>Subcategories</th>
                                    <th>Created</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-dark">#{{ $category->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $category->attribute_data['name'] ?? 'N/A' }}</h6>
                                                    @if(isset($category->attribute_data['description']))
                                                        <small class="text-muted">{{ Str::limit($category->attribute_data['description'], 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($category->group)
                                                <span class="badge bg-info">{{ $category->group->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->parent_id)
                                                @php
                                                    $parent = \App\Models\Configuration\Collection::find($category->parent_id);
                                                @endphp
                                                @if($parent)
                                                    <span class="badge bg-light-warning text-dark">{{ $parent->attribute_data['name'] ?? 'N/A' }}</span>
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            @else
                                                <span class="badge bg-success">Root</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($category->type === 'static')
                                                <span class="badge bg-primary">Static</span>
                                            @else
                                                <span class="badge bg-warning">Dynamic</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-dark">
                                                <i class="bi bi-box me-1"></i>{{ $category->products->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $children = \App\Models\Configuration\Collection::where('parent_id', $category->id)->count();
                                            @endphp
                                            <span class="badge bg-light-success text-dark">
                                                <i class="bi bi-diagram-3 me-1"></i>{{ $children }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $category->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editCategory({{ $category->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->attribute_data['name'] ?? 'N/A') }}')"
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
                                            <h5 class="text-muted">No categories found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first category</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Category
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($categories->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries
                            </div>
                            <div>
                                {{ $categories->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.categories.partials.create-modal')
@include('admin.pages.categories.partials.edit-modal')
@include('admin.pages.categories.partials.delete-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Edit Category Function
    function editCategory(categoryId) {
        // Fetch category data via AJAX
        fetch(`/admin/categories/${categoryId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const category = data.category;

                    // Populate form fields
                    document.getElementById('edit_category_id').value = category.id;
                    document.getElementById('edit_collection_group_id').value = category.collection_group_id;
                    document.getElementById('edit_name').value = category.attribute_data.name || '';
                    document.getElementById('edit_parent_id').value = category.parent_id || '';
                    document.getElementById('edit_type').value = category.type;
                    document.getElementById('edit_sort').value = category.sort;
                    document.getElementById('edit_description').value = category.attribute_data.description || '';

                    // Update form action
                    document.getElementById('editCategoryForm').action = `/admin/categories/${category.id}`;

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                    editModal.show();
                } else {
                    alert('Error loading category data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading category data');
            });
    }

    // Delete Category Function
    function deleteCategory(categoryId, categoryName) {
        document.getElementById('delete_category_name').textContent = categoryName;
        document.getElementById('deleteCategoryForm').action = `/admin/categories/${categoryId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
        deleteModal.show();
    }
</script>
@endsection
