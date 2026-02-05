@extends('admin.components.app')
@section('title', 'Edit Attribute')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div>
                <h2>Edit Attribute</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Attributes</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                    <form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="deleted_values" id="deletedValues">

                        <div class="mb-4">
                            <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $attribute->name) }}" required autofocus
                                   placeholder="e.g., Hashrate, Color, Power Consumption">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label">Attribute Values</label>
                            <small class="text-muted d-block mb-2">Manage the possible values for this attribute</small>
                        </div>

                        <div id="valuesContainer">
                            @forelse($attribute->values as $index => $value)
                                <div class="value-row mb-2" data-value-id="{{ $value->id }}">
                                    <div class="input-group">
                                        <span class="input-group-text drag-handle" style="cursor: move;">
                                            <i class="bi bi-grip-vertical"></i>
                                        </span>
                                        <input type="hidden" name="values[{{ $index }}][id]" value="{{ $value->id }}">
                                        <input type="hidden" name="values[{{ $index }}][sort_order]" value="{{ $value->sort_order }}" class="sort-order-input">
                                        <input type="text" name="values[{{ $index }}][value]" class="form-control"
                                               value="{{ old('values.'.$index.'.value', $value->value) }}"
                                               placeholder="e.g., 110 TH/s">
                                        <button type="button" class="btn btn-outline-danger remove-value">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="value-row mb-2">
                                    <div class="input-group">
                                        <input type="text" name="values[0][value]" class="form-control" placeholder="e.g., 110 TH/s">
                                        <button type="button" class="btn btn-outline-danger remove-value" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <button type="button" id="addValue" class="btn btn-outline-primary btn-sm mb-4">
                            <i class="bi bi-plus-circle me-1"></i> Add Another Value
                        </button>

                        <div class="alert alert-info">
                            <small><strong>Tip:</strong> You can drag and drop to reorder values.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Attribute</button>
                            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const valuesContainer = document.getElementById('valuesContainer');
    const addValueBtn = document.getElementById('addValue');
    const deletedValuesInput = document.getElementById('deletedValues');
    let deletedValues = [];
    let newValueIndex = {{ $attribute->values->count() }};

    // Make values sortable
    new Sortable(valuesContainer, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function() {
            updateSortOrder();
        }
    });

    // Update sort order after drag and drop
    function updateSortOrder() {
        const rows = valuesContainer.querySelectorAll('.value-row');
        rows.forEach((row, index) => {
            const sortOrderInput = row.querySelector('.sort-order-input');
            if (sortOrderInput) {
                sortOrderInput.value = index;
            }
        });
    }

    // Add new value input
    addValueBtn.addEventListener('click', function() {
        const valueRow = document.createElement('div');
        valueRow.className = 'value-row mb-2';
        valueRow.innerHTML = `
            <div class="input-group">
                <span class="input-group-text drag-handle" style="cursor: move;">
                    <i class="bi bi-grip-vertical"></i>
                </span>
                <input type="hidden" name="values[${newValueIndex}][sort_order]" value="${newValueIndex}" class="sort-order-input">
                <input type="text" name="values[${newValueIndex}][value]" class="form-control" placeholder="e.g., 110 TH/s">
                <button type="button" class="btn btn-outline-danger remove-value">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        valuesContainer.appendChild(valueRow);
        newValueIndex++;
    });

    // Remove value input
    valuesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-value')) {
            const valueRow = e.target.closest('.value-row');
            const totalRows = valuesContainer.querySelectorAll('.value-row').length;

            if (totalRows > 1) {
                const valueId = valueRow.dataset.valueId;
                if (valueId) {
                    deletedValues.push(valueId);
                    deletedValuesInput.value = JSON.stringify(deletedValues);
                }
                valueRow.remove();
                updateSortOrder();
            }
        }
    });
});
</script>
@endpush
@endsection
