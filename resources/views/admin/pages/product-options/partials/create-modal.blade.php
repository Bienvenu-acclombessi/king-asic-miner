<!-- Create Product Option Modal -->
<div class="modal fade" id="createProductOptionModal" tabindex="-1" aria-labelledby="createProductOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.product-options.store') }}" method="POST" id="createProductOptionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductOptionModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Product Option
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Option Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="e.g., Color, Size" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_label" class="form-label">Label</label>
                        <input type="text" name="label" id="create_label" class="form-control" placeholder="e.g., Choose a color">
                        <small class="text-muted">Optional display label for the option.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_handle" class="form-label">Handle</label>
                        <input type="text" name="handle" id="create_handle" class="form-control" placeholder="e.g., color">
                        <small class="text-muted">Unique identifier (leave empty to auto-generate from name).</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="shared" id="create_shared" value="1">
                            <label class="form-check-label" for="create_shared">
                                Shared Option
                            </label>
                        </div>
                        <small class="text-muted">Shared options can be used across multiple products.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Product Option
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
