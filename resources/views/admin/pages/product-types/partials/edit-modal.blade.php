<!-- Edit Product Type Modal -->
<div class="modal fade" id="editProductTypeModal" tabindex="-1" aria-labelledby="editProductTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="editProductTypeForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_product_type_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductTypeModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Product Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Product Type Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" placeholder="e.g., ASIC Miner" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Product Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
