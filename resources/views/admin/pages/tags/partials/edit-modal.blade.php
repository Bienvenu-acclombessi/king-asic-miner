<!-- Edit Tag Modal -->
<div class="modal fade" id="editTagModal" tabindex="-1" aria-labelledby="editTagModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editTagForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editTagModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Tag
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tag_id" id="edit_tag_id">

                    <div class="mb-3">
                        <label for="edit_value" class="form-label">Tag Name <span class="text-danger">*</span></label>
                        <input type="text" name="value" id="edit_value" class="form-control" required>
                    </div>

                    <!-- Current Image Display -->
                    <div id="edit_image_preview_container" style="display: none;" class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="text-center mb-2">
                            <img id="edit_current_image" src="" alt="Current Image" class="rounded" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                            <label class="form-check-label text-danger" for="remove_image">
                                Remove this image
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Change Image <span class="text-muted">(Optional)</span></label>
                        <input type="file" name="image" id="edit_image" class="form-control" accept="image/*">
                        <small class="text-muted">Upload a new image to replace the current one (JPEG, PNG, JPG, GIF, SVG - Max: 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
