<!-- Add Tax Rate Modal -->
<div class="modal fade" id="addTaxRateModal" tabindex="-1" aria-labelledby="addTaxRateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.tax-rate-amounts.store') }}" method="POST" id="addTaxRateForm">
                @csrf
                <input type="hidden" name="tax_class_id" value="{{ $taxClass->id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="addTaxRateModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add Tax Rate
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tax_rate_id" class="form-label">Tax Rate <span class="text-danger">*</span></label>
                        <select name="tax_rate_id" id="tax_rate_id" class="form-select" required>
                            <option value="">Select a tax rate</option>
                            @foreach($availableTaxRates as $taxRate)
                                <option value="{{ $taxRate->id }}">
                                    {{ $taxRate->name }} ({{ $taxRate->taxZone->name }}) - Priority: {{ $taxRate->priority }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select the tax rate to apply for this class.</small>
                    </div>

                    <div class="mb-3">
                        <label for="percentage" class="form-label">Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="percentage" id="percentage" class="form-control"
                                   step="0.001" min="0" max="100" placeholder="e.g., 20.000" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">The tax percentage for this class (e.g., 20 for 20%).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Add Tax Rate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
