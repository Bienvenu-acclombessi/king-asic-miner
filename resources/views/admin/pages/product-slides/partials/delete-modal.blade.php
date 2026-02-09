<!-- Delete Product Slide Modal -->
<div class="modal fade" id="deleteProductSlideModal" tabindex="-1" aria-labelledby="deleteProductSlideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="deleteProductSlideForm">
                @csrf
                @method('DELETE')

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteProductSlideModalLabel">
                        <i class="bi bi-trash me-2"></i>Delete Product Slide
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>

                    <p class="mb-3">Are you sure you want to delete this product slide?</p>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-2">Slide Details:</h6>
                            <ul class="mb-0">
                                <li><strong>Product:</strong> <span id="delete_product_name"></span></li>
                                <li class="text-muted">The background image will also be deleted from storage</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
