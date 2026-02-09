<div class="modal fade" id="deleteShippingMethodModal" tabindex="-1" aria-labelledby="deleteShippingMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteShippingMethodModalLabel">Delete Shipping Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="delete_shipping_method_id">
                <p>Are you sure you want to delete the shipping method <strong id="delete_shipping_method_name"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This action cannot be undone. Carts using this method will need to select a new shipping method.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
