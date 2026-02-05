<!-- Delete Brand Modal -->
<div class="modal fade" id="deleteBrandModal" tabindex="-1" aria-labelledby="deleteBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteBrandForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteBrandModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-center mb-3">Are you sure you want to delete this brand?</h5>
                    <p class="text-center text-muted mb-0">
                        Brand: <strong id="delete_brand_name"></strong>
                    </p>
                    <p class="text-center text-danger mt-2">
                        <small>This action cannot be undone. All product associations will be affected.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
