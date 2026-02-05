@extends('admin.components.app')
@section('title', 'Tax Class Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-calculator me-2"></i>{{ $taxClass->name }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.tax-classes.index') }}">Tax Classes</a></li>
                            <li class="breadcrumb-item active">{{ $taxClass->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('admin.tax-classes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to List
                    </a>
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

    <div class="row">
        <!-- Tax Class Info -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Tax Class Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Name</label>
                        <h6>{{ $taxClass->name }}</h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Status</label>
                        <div>
                            @if($taxClass->default)
                                <span class="badge bg-success">
                                    <i class="bi bi-star-fill me-1"></i>Default Tax Class
                                </span>
                            @else
                                <span class="badge bg-secondary">Standard</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Created</label>
                        <p class="mb-0">{{ $taxClass->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-muted small">Last Updated</label>
                        <p class="mb-0">{{ $taxClass->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Rate Amounts -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-percent me-2"></i>Tax Rates</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTaxRateModal">
                            <i class="bi bi-plus-circle me-1"></i>Add Tax Rate
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered text-nowrap mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Tax Rate</th>
                                    <th>Tax Zone</th>
                                    <th>Priority</th>
                                    <th>Percentage</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxClass->taxRateAmounts as $rateAmount)
                                    <tr>
                                        <td class="ps-4">
                                            <h6 class="mb-0">{{ $rateAmount->taxRate->name }}</h6>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $rateAmount->taxRate->taxZone->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-dark">{{ $rateAmount->taxRate->priority }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-success">{{ number_format($rateAmount->percentage, 2) }}%</strong>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="editTaxRateAmount({{ $rateAmount->id }})"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteTaxRateAmount({{ $rateAmount->id }}, '{{ addslashes($rateAmount->taxRate->name) }}')"
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
                                            <h6 class="text-muted">No tax rates assigned</h6>
                                            <p class="text-muted mb-3">Add tax rates to define how this class is taxed</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaxRateModal">
                                                <i class="bi bi-plus-circle me-1"></i>Add Tax Rate
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Tax Rate Modal -->
@include('admin.pages.tax-classes.partials.add-tax-rate-modal')
<!-- Edit Tax Rate Amount Modal -->
@include('admin.pages.tax-classes.partials.edit-tax-rate-amount-modal')
<!-- Delete Tax Rate Amount Modal -->
@include('admin.pages.tax-classes.partials.delete-tax-rate-amount-modal')
@endsection

@section('js')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Edit Tax Rate Amount Function
    function editTaxRateAmount(rateAmountId) {
        fetch(`/admin/tax-rate-amounts/${rateAmountId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const rateAmount = data.rateAmount;

                    document.getElementById('edit_rate_amount_id').value = rateAmount.id;
                    document.getElementById('edit_percentage').value = rateAmount.percentage;
                    document.getElementById('edit_tax_rate_name').textContent = rateAmount.tax_rate.name;

                    document.getElementById('editTaxRateAmountForm').action = `/admin/tax-rate-amounts/${rateAmount.id}`;

                    const editModal = new bootstrap.Modal(document.getElementById('editTaxRateAmountModal'));
                    editModal.show();
                } else {
                    alert('Error loading tax rate amount data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading tax rate amount data');
            });
    }

    // Delete Tax Rate Amount Function
    function deleteTaxRateAmount(rateAmountId, rateName) {
        document.getElementById('delete_rate_name').textContent = rateName;
        document.getElementById('deleteTaxRateAmountForm').action = `/admin/tax-rate-amounts/${rateAmountId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteTaxRateAmountModal'));
        deleteModal.show();
    }
</script>
@endsection
