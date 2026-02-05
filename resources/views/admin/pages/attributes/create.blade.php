@extends('admin.components.app')
@section('title', 'Add New Attribute')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div>
                <h2>Add New Attribute</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Attribute Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attributes.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required autofocus
                                   placeholder="e.g., Hashrate, Color, Power Consumption">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">The name of the attribute (e.g., Size, Color, Hashrate)</small>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label">Attribute Values</label>
                            <small class="text-muted d-block mb-2">Add the possible values for this attribute</small>
                        </div>

                        <div id="valuesContainer">
                            <!-- Value inputs will be added here -->
                            <div class="value-row mb-2">
                                <div class="input-group">
                                    <input type="text" name="values[]" class="form-control" placeholder="e.g., 110 TH/s">
                                    <button type="button" class="btn btn-outline-danger remove-value" disabled>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="addValue" class="btn btn-outline-primary btn-sm mb-4">
                            <i class="bi bi-plus-circle me-1"></i> Add Another Value
                        </button>

                        <div class="alert alert-info">
                            <small><strong>Tip:</strong> You can add values now or later when editing the attribute.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Attribute</button>
                            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const valuesContainer = document.getElementById('valuesContainer');
    const addValueBtn = document.getElementById('addValue');

    // Add new value input
    addValueBtn.addEventListener('click', function() {
        const valueRow = document.createElement('div');
        valueRow.className = 'value-row mb-2';
        valueRow.innerHTML = `
            <div class="input-group">
                <input type="text" name="values[]" class="form-control" placeholder="e.g., 110 TH/s">
                <button type="button" class="btn btn-outline-danger remove-value">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        valuesContainer.appendChild(valueRow);
    });

    // Remove value input (using event delegation)
    valuesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-value')) {
            const valueRow = e.target.closest('.value-row');
            const totalRows = valuesContainer.querySelectorAll('.value-row').length;

            if (totalRows > 1) {
                valueRow.remove();
            }
        }
    });
});
</script>
@endpush
@endsection
