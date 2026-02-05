@extends('admin.components.app')
@section('title', 'Tags Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-tags me-2"></i>Tags</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tags</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Tag
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
                    <form action="{{ route('admin.tags.index') }}" method="GET" class="row g-3">
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control" placeholder="Search by tag name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tags Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Tags List</h5>
                        <span class="badge bg-primary">{{ $tags->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Image</th>
                                    <th>Tag Name</th>
                                    <th>Created</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tags as $tag)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-dark">#{{ $tag->id }}</span>
                                        </td>
                                        <td>
                                            @if($tag->image)
                                                <img src="{{ asset('storage/' . $tag->image) }}"
                                                     alt="{{ $tag->value }}"
                                                     class="rounded"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="bi bi-tag text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <h6 class="mb-0">{{ $tag->value }}</h6>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $tag->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editTag({{ $tag->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteTag({{ $tag->id }}, '{{ addslashes($tag->value) }}')"
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
                                            <h5 class="text-muted">No tags found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first tag</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Tag
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($tags->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $tags->firstItem() }} to {{ $tags->lastItem() }} of {{ $tags->total() }} entries
                            </div>
                            <div>
                                {{ $tags->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.tags.partials.create-modal')
@include('admin.pages.tags.partials.edit-modal')
@include('admin.pages.tags.partials.delete-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Edit Tag Function
    function editTag(tagId) {
        fetch(`/admin/tags/${tagId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tag = data.tag;

                    // Populate form fields
                    document.getElementById('edit_tag_id').value = tag.id;
                    document.getElementById('edit_value').value = tag.value || '';

                    // Handle image preview
                    const imagePreview = document.getElementById('edit_image_preview_container');
                    const currentImage = document.getElementById('edit_current_image');

                    if (tag.image) {
                        currentImage.src = `/storage/${tag.image}`;
                        imagePreview.style.display = 'block';
                    } else {
                        imagePreview.style.display = 'none';
                    }

                    // Update form action
                    document.getElementById('editTagForm').action = `/admin/tags/${tag.id}`;

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editTagModal'));
                    editModal.show();
                } else {
                    alert('Error loading tag data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading tag data');
            });
    }

    // Delete Tag Function
    function deleteTag(tagId, tagValue) {
        document.getElementById('delete_tag_name').textContent = tagValue;
        document.getElementById('deleteTagForm').action = `/admin/tags/${tagId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteTagModal'));
        deleteModal.show();
    }

    // Image preview for create modal
    document.getElementById('image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image_preview').src = e.target.result;
                document.getElementById('image_preview_container').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Image preview for edit modal
    document.getElementById('edit_image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('edit_current_image').src = e.target.result;
                document.getElementById('edit_image_preview_container').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
