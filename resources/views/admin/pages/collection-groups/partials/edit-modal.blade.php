<!-- Edit Collection Group Modal -->
<div class="modal fade" id="editCollectionGroupModal" tabindex="-1" aria-labelledby="editCollectionGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editCollectionGroupForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editCollectionGroupModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Collection Group
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="group_id" id="edit_group_id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                        <small class="text-muted">This is the display name of the collection group.</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_handle" class="form-label">Handle <span class="text-danger">*</span></label>
                        <input type="text" name="handle" id="edit_handle" class="form-control" required>
                        <small class="text-muted">A unique identifier used in URLs (lowercase, no spaces).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-generate handle from name for edit modal
    document.getElementById('edit_name')?.addEventListener('input', function(e) {
        const name = e.target.value;
        const handle = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        document.getElementById('edit_handle').value = handle;
    });
</script>
