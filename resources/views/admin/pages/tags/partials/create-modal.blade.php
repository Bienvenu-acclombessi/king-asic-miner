<!-- Create Tag Modal -->
<div class="modal fade" id="createTagModal" tabindex="-1" aria-labelledby="createTagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.tags.store') }}" method="POST" id="createTagForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createTagModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Tag
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="value" class="form-label">Tag Name <span class="text-danger">*</span></label>
                        <input type="text" name="value" id="value" class="form-control" placeholder="e.g., New Arrival" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Tag Image <span class="text-muted">(Optional)</span></label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        <small class="text-muted">Upload an image for this tag (JPEG, PNG, JPG, GIF, SVG - Max: 2MB)</small>
                    </div>

                    <!-- Image Preview -->
                    <div id="image_preview_container" style="display: none;" class="mt-3">
                        <label class="form-label">Image Preview</label>
                        <div class="text-center">
                            <img id="image_preview" src="" alt="Preview" class="rounded" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
