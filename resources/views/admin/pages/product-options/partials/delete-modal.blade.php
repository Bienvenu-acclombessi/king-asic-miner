<!-- Delete Product Option Modal -->
<div class="modal fade" id="deleteProductOptionModal" tabindex="-1" aria-labelledby="deleteProductOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="deleteProductOptionForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteProductOptionModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Delete Product Option
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>
                    <p>Are you sure you want to delete the product option <strong id="delete_product_option_name"></strong>?</p>
                    <p class="text-muted mb-0">This will fail if the product option has associated products or option values.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete Product Option
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
