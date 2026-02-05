<!-- Edit Tax Class Modal -->
<div class="modal fade" id="editTaxClassModal" tabindex="-1" aria-labelledby="editTaxClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editTaxClassForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxClassModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Tax Class
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tax_class_id" id="edit_tax_class_id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Tax Class Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="default" id="edit_default" value="1">
                            <label class="form-check-label" for="edit_default">
                                <strong>Set as Default</strong>
                            </label>
                            <small class="d-block text-muted">Make this the default tax class for new products.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Tax Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
