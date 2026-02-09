<!-- Create Product Slide Modal -->
<div class="modal fade" id="createProductSlideModal" tabindex="-1" aria-labelledby="createProductSlideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('admin.product-slides.store') }}" method="POST" enctype="multipart/form-data" id="createProductSlideForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createProductSlideModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Product Slide
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Product Selection -->
                        <div class="col-12">
                            <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="">Select a product</option>
                            </select>
                            <small class="text-muted">Select the product to display in this slide</small>
                        </div>

                        <!-- Background Image -->
                        <div class="col-12">
                            <label for="background_image" class="form-label">Background Image <span class="text-danger">*</span></label>
                            <input type="file" name="background_image" id="background_image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Recommended size: 1920x400px (JPG, PNG, WEBP - Max 5MB)</small>
                            <div id="background_preview" class="mt-2"></div>
                        </div>

                        <!-- Is Active -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">
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
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Create Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
