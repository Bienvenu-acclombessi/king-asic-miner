<!-- Create Product Type Modal -->
<div class="modal fade" id="createProductTypeModal" tabindex="-1" aria-labelledby="createProductTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.product-types.store') }}" method="POST" id="createProductTypeForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductTypeModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Product Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Product Type Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="e.g., ASIC Miner" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Product Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
