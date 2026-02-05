<!-- Edit Tax Rate Amount Modal -->
<div class="modal fade" id="editTaxRateAmountModal" tabindex="-1" aria-labelledby="editTaxRateAmountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editTaxRateAmountForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxRateAmountModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Tax Rate Percentage
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="rate_amount_id" id="edit_rate_amount_id">

                    <div class="mb-3">
                        <label class="form-label">Tax Rate</label>
                        <p class="form-control-plaintext"><strong id="edit_tax_rate_name"></strong></p>
                        <small class="text-muted">Tax rate cannot be changed. Delete and add a new one instead.</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_percentage" class="form-label">Percentage <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="percentage" id="edit_percentage" class="form-control"
                                   step="0.001" min="0" max="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">The tax percentage for this class.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Percentage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
