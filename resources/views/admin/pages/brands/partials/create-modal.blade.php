<!-- Create Brand Modal -->
<div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.brands.store') }}" method="POST" id="createBrandForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createBrandModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Brand
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="e.g., Bitmain, MicroBT" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_description" class="form-label">Description</label>
                        <textarea name="description" id="create_description" class="form-control" rows="3" placeholder="Optional brand description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="create_website" class="form-label">Website</label>
                        <input type="url" name="website" id="create_website" class="form-control" placeholder="https://example.com">
                        <small class="text-muted">Optional brand website URL.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
