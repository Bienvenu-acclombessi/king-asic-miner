<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST" id="createCategoryForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_collection_group_id" class="form-label">Collection Group <span class="text-danger">*</span></label>
                        <select name="collection_group_id" id="create_collection_group_id" class="form-select" required>
                            <option value="">Select a collection group</option>
                            @foreach($collectionGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select the group this category belongs to.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="e.g., ASIC Miners" required>
                        <small class="text-muted">The name is how it appears on your site.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_parent_id" class="form-label">Parent Category</label>
                        <select name="parent_id" id="create_parent_id" class="form-select">
                            <option value="">None (Root Category)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->attribute_data['name'] ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Assign a parent to make this a subcategory.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_type" class="form-label">Type</label>
                        <select name="type" id="create_type" class="form-select">
                            <option value="static" selected>Static</option>
                            <option value="dynamic">Dynamic</option>
                        </select>
                        <small class="text-muted">Static categories are managed manually.</small>
                    </div>

                    <div class="mb-3">
                        <label for="create_sort" class="form-label">Sort Order</label>
                        <select name="sort" id="create_sort" class="form-select">
                            <option value="custom" selected>Custom</option>
                            <option value="alphabetical">Alphabetical</option>
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="create_description" class="form-label">Description</label>
                        <textarea name="description" id="create_description" class="form-control" rows="3" placeholder="Optional category description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
