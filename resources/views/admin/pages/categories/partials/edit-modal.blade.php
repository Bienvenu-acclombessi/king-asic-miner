<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="category_id" id="edit_category_id">

                    <div class="mb-3">
                        <label for="edit_collection_group_id" class="form-label">Collection Group <span class="text-danger">*</span></label>
                        <select name="collection_group_id" id="edit_collection_group_id" class="form-select" required>
                            <option value="">Select a collection group</option>
                            @foreach($collectionGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_parent_id" class="form-label">Parent Category</label>
                        <select name="parent_id" id="edit_parent_id" class="form-select">
                            <option value="">None (Root Category)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->attribute_data['name'] ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type</label>
                        <select name="type" id="edit_type" class="form-select">
                            <option value="static">Static</option>
                            <option value="dynamic">Dynamic</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_sort" class="form-label">Sort Order</label>
                        <select name="sort" id="edit_sort" class="form-select">
                            <option value="custom">Custom</option>
                            <option value="alphabetical">Alphabetical</option>
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
