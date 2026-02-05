@extends('admin.components.app')
@section('title', 'Collection Groups Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-collection me-2"></i>Collection Groups</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Collection Groups</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCollectionGroupModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Group
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

    <!-- Search Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.collection-groups.index') }}" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or handle..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.collection-groups.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection Groups Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Groups List</h5>
                        <span class="badge bg-primary">{{ $collectionGroups->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Handle</th>
                                    <th>Collections</th>
                                    <th>Created</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collectionGroups as $group)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-dark">#{{ $group->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-shape icon-sm bg-light-primary text-primary rounded-circle me-3">
                                                    <i class="bi bi-collection"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $group->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-primary">{{ $group->handle }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-success text-dark">
                                                <i class="bi bi-folder2-open me-1"></i>{{ $group->collections->count() }} categories
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $group->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editCollectionGroup({{ $group->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteCollectionGroup({{ $group->id }}, '{{ addslashes($group->name) }}')"
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
                                            <h5 class="text-muted">No collection groups found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first collection group</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCollectionGroupModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Group
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($collectionGroups->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $collectionGroups->firstItem() }} to {{ $collectionGroups->lastItem() }} of {{ $collectionGroups->total() }} entries
                            </div>
                            <div>
                                {{ $collectionGroups->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.collection-groups.partials.create-modal')
@include('admin.pages.collection-groups.partials.edit-modal')
@include('admin.pages.collection-groups.partials.delete-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Edit Collection Group Function
    function editCollectionGroup(groupId) {
        // Fetch group data via AJAX
        fetch(`/admin/collection-groups/${groupId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const group = data.group;

                    // Populate form fields
                    document.getElementById('edit_group_id').value = group.id;
                    document.getElementById('edit_name').value = group.name;
                    document.getElementById('edit_handle').value = group.handle;

                    // Update form action
                    document.getElementById('editCollectionGroupForm').action = `/admin/collection-groups/${group.id}`;

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editCollectionGroupModal'));
                    editModal.show();
                } else {
                    alert('Error loading group data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading group data');
            });
    }

    // Delete Collection Group Function
    function deleteCollectionGroup(groupId, groupName) {
        document.getElementById('delete_group_name').textContent = groupName;
        document.getElementById('deleteCollectionGroupForm').action = `/admin/collection-groups/${groupId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteCollectionGroupModal'));
        deleteModal.show();
    }
</script>

<style>
    .icon-shape {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .icon-sm {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }

    .bg-light-primary {
        background-color: rgba(99, 102, 241, 0.1) !important;
    }

    .bg-light-success {
        background-color: rgba(16, 185, 129, 0.1) !important;
    }
</style>
@endsection
