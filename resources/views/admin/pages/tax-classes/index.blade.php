@extends('admin.components.app')
@section('title', 'Tax Classes Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-calculator me-2"></i>Tax Classes</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tax Classes</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaxClassModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Tax Class
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
                    <form action="{{ route('admin.tax-classes.index') }}" method="GET" class="row g-3">
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control" placeholder="Search by tax class name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.tax-classes.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tax Classes Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Tax Classes List</h5>
                        <span class="badge bg-primary">{{ $taxClasses->total() }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Tax Rates</th>
                                    <th>Created</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxClasses as $taxClass)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light-primary text-dark">#{{ $taxClass->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $taxClass->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($taxClass->default)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-star-fill me-1"></i>Default
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Standard</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light-info text-dark">
                                                <i class="bi bi-percent me-1"></i>{{ $taxClass->tax_rate_amounts_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $taxClass->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.tax-classes.show', $taxClass->id) }}"
                                                   class="btn btn-sm btn-outline-info"
                                                   data-bs-toggle="tooltip" title="View & Manage Rates">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editTaxClass({{ $taxClass->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteTaxClass({{ $taxClass->id }}, '{{ addslashes($taxClass->name) }}')"
                                                        data-bs-toggle="tooltip" title="Delete"
                                                        {{ $taxClass->default ? 'disabled' : '' }}>
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
                                            <h5 class="text-muted">No tax classes found</h5>
                                            <p class="text-muted mb-3">Get started by adding your first tax class</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaxClassModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Tax Class
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($taxClasses->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $taxClasses->firstItem() }} to {{ $taxClasses->lastItem() }} of {{ $taxClasses->total() }} entries
                            </div>
                            <div>
                                {{ $taxClasses->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.tax-classes.partials.create-modal')
@include('admin.pages.tax-classes.partials.edit-modal')
@include('admin.pages.tax-classes.partials.delete-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Edit Tax Class Function
    function editTaxClass(taxClassId) {
        // Fetch tax class data via AJAX
        fetch(`/admin/tax-classes/${taxClassId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const taxClass = data.taxClass;

                    // Populate form fields
                    document.getElementById('edit_tax_class_id').value = taxClass.id;
                    document.getElementById('edit_name').value = taxClass.name || '';
                    document.getElementById('edit_default').checked = taxClass.default;

                    // Update form action
                    document.getElementById('editTaxClassForm').action = `/admin/tax-classes/${taxClass.id}`;

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editTaxClassModal'));
                    editModal.show();
                } else {
                    alert('Error loading tax class data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading tax class data');
            });
    }

    // Delete Tax Class Function
    function deleteTaxClass(taxClassId, taxClassName) {
        document.getElementById('delete_tax_class_name').textContent = taxClassName;
        document.getElementById('deleteTaxClassForm').action = `/admin/tax-classes/${taxClassId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteTaxClassModal'));
        deleteModal.show();
    }
</script>
@endsection
