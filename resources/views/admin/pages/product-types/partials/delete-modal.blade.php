<!-- Delete Product Type Modal -->
<div class="modal fade" id="deleteProductTypeModal" tabindex="-1" aria-labelledby="deleteProductTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="deleteProductTypeForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteProductTypeModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Delete Product Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>
                    <p>Are you sure you want to delete the product type <strong id="delete_product_type_name"></strong>?</p>
                    <p class="text-muted mb-0">This will fail if the product type has associated products.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete Product Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
