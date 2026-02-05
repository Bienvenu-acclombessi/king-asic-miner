<!-- Create Tax Class Modal -->
<div class="modal fade" id="createTaxClassModal" tabindex="-1" aria-labelledby="createTaxClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.tax-classes.store') }}" method="POST" id="createTaxClassForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaxClassModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Tax Class
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Tax Class Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="e.g., Standard Rate, Reduced Rate" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="default" id="create_default" value="1">
                            <label class="form-check-label" for="create_default">
                                <strong>Set as Default</strong>
                            </label>
                            <small class="d-block text-muted">Make this the default tax class for new products.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Tax Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
