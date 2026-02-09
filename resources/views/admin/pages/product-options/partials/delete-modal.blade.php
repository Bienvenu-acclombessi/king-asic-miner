<!-- Delete Product Option Modal -->
<div class="modal fade" id="deleteProductOptionModal" tabindex="-1" aria-labelledby="deleteProductOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <form action="" method="POST" id="deleteProductOptionForm">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger bg-opacity-10 border-danger">
                    <h5 class="modal-title text-danger" id="deleteProductOptionModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Product Option
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
                        <div>
                            <strong>Warning!</strong> This action is permanent and cannot be undone.
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="mb-2">You are about to delete the following product option:</p>
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="mb-0">
                                    <i class="bi bi-sliders me-2 text-primary"></i>
                                    <strong id="delete_product_option_name"></strong>
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4" id="delete_option_stats">
                        <h6 class="mb-3">Impact Assessment:</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card border-primary">
                                    <div class="card-body p-3 text-center">
                                        <i class="bi bi-box-seam fs-4 text-primary mb-2 d-block"></i>
                                        <h5 class="mb-0" id="delete_products_count">-</h5>
                                        <small class="text-muted">Products Using</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-info">
                                    <div class="card-body p-3 text-center">
                                        <i class="bi bi-list-check fs-4 text-info mb-2 d-block"></i>
                                        <h5 class="mb-0" id="delete_values_count">-</h5>
                                        <small class="text-muted">Option Values</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning" id="delete_warning_message">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Important:</strong> <span id="delete_warning_text">Deleting this option will also remove all associated values and relationships with products.</span>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="delete_confirmation_check" required>
                        <label class="form-check-label" for="delete_confirmation_check">
                            I understand this action cannot be undone
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger" id="delete_confirm_button" disabled>
                        <i class="bi bi-trash-fill me-1"></i>Delete Permanently
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Enable delete button only when checkbox is checked
document.getElementById('delete_confirmation_check')?.addEventListener('change', function() {
    document.getElementById('delete_confirm_button').disabled = !this.checked;
});

// Reset confirmation checkbox when modal is closed
document.getElementById('deleteProductOptionModal')?.addEventListener('hidden.bs.modal', function() {
    const checkbox = document.getElementById('delete_confirmation_check');
    if (checkbox) {
        checkbox.checked = false;
    }
    document.getElementById('delete_confirm_button').disabled = true;
});
</script>
