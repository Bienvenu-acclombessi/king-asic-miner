<!-- Create Collection Group Modal -->
<div class="modal fade" id="createCollectionGroupModal" tabindex="-1" aria-labelledby="createCollectionGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.collection-groups.store') }}" method="POST" id="createCollectionGroupForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCollectionGroupModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Collection Group
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="e.g., Mining Equipment" required autofocus>
                        <small class="text-muted">This is the display name of the collection group.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_handle" class="form-label">Handle <span class="text-danger">*</span></label>
                        <input type="text" name="handle" id="create_handle" class="form-control" placeholder="e.g., mining-equipment" required>
                        <small class="text-muted">A unique identifier used in URLs (lowercase, no spaces).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-generate handle from name
    document.getElementById('create_name')?.addEventListener('input', function(e) {
        const name = e.target.value;
        const handle = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        document.getElementById('create_handle').value = handle;
    });
</script>
