<!-- Delete Minable Coin Modal -->
<div class="modal fade" id="deleteMinableCoinModal" tabindex="-1" aria-labelledby="deleteMinableCoinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteMinableCoinModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to delete this minable coin?</p>
                    <div class="alert alert-warning">
                        <strong><i class="bi bi-coin me-2"></i><span id="delete-coin-name"></span></strong>
                    </div>
                    <p class="text-danger mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Warning:</strong> This action cannot be undone. The coin will be permanently deleted.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete Coin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
