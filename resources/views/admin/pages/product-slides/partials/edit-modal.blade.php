<!-- Edit Product Slide Modal -->
<div class="modal fade" id="editProductSlideModal" tabindex="-1" aria-labelledby="editProductSlideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data" id="editProductSlideForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="product_slide_id" id="edit_product_slide_id">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editProductSlideModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Product Slide
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Product Selection -->
                        <div class="col-12">
                            <label for="edit_product_id" class="form-label">Product <span class="text-danger">*</span></label>
                            <select name="product_id" id="edit_product_id" class="form-select" required>
                                <option value="">Select a product</option>
                            </select>
                            <small class="text-muted">Select the product to display in this slide</small>
                        </div>

                        <!-- Current Background Image -->
                        <div class="col-12">
                            <label class="form-label">Current Background Image</label>
                            <div id="current_background_preview"></div>
                        </div>

                        <!-- New Background Image -->
                        <div class="col-12">
                            <label for="edit_background_image" class="form-label">New Background Image</label>
                            <input type="file" name="background_image" id="edit_background_image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image. Recommended size: 1920x400px (JPG, PNG, WEBP - Max 5MB)</small>
                            <div id="edit_background_preview" class="mt-2"></div>
                        </div>

                        <!-- Is Active -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">
                                    Active (Display on homepage)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i>Update Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
