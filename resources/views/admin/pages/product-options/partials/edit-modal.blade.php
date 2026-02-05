<!-- Edit Product Option Modal -->
<div class="modal fade" id="editProductOptionModal" tabindex="-1" aria-labelledby="editProductOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="editProductOptionForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_product_option_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductOptionModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Product Option
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Option Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" placeholder="e.g., Color, Size" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_label" class="form-label">Label</label>
                        <input type="text" name="label" id="edit_label" class="form-control" placeholder="e.g., Choose a color">
                        <small class="text-muted">Optional display label for the option.</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_handle" class="form-label">Handle</label>
                        <input type="text" name="handle" id="edit_handle" class="form-control" placeholder="e.g., color">
                        <small class="text-muted">Unique identifier.</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="shared" id="edit_shared" value="1">
                            <label class="form-check-label" for="edit_shared">
                                Shared Option
                            </label>
                        </div>
                        <small class="text-muted">Shared options can be used across multiple products.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Product Option
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
