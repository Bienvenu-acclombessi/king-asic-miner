<div class="step-content">
    <h4 class="mb-3">Images & Media</h4>
    <p class="text-muted mb-4">Upload product images (drag & drop or click to browse)</p>

    <!-- Upload Area -->
    <div class="upload-area mb-4" id="uploadArea">
        <div class="upload-placeholder text-center py-5">
            <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
            <h5 class="mt-3">Drag & Drop Images Here</h5>
            <p class="text-muted">or click to browse</p>
            <p class="text-muted small">Accepted formats: JPG, PNG, WEBP | Max size: 5MB per image</p>
            <input type="file" id="imageInput" name="images[]" multiple accept="image/jpg,image/jpeg,image/png,image/webp" style="display: none;">
            <button type="button" class="btn btn-primary mt-2" id="browseImagesBtn">
                <i class="bi bi-folder2-open"></i> Browse Files
            </button>
        </div>
    </div>

    <!-- Image Preview Grid -->
    <div id="imagePreviewGrid" class="row g-3" style="display: none;">
        <!-- Image previews will be added here -->
    </div>

    <!-- Info Alert -->
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        The first image will be used as the product thumbnail. You can reorder images by dragging them.
    </div>
</div>
