<div class="step-content">
    <h4 class="mb-3">Images & Media</h4>
    <p class="text-muted mb-4">Update product images</p>

    <!-- Thumbnail Image (Main Image) -->
    <div class="mb-4">
        <label class="form-label">Main Image (Thumbnail) <span class="text-danger">*</span></label>
        <div class="card">
            <div class="card-body">
                <input type="file" id="thumbnailInput" name="thumbnail" accept="image/jpg,image/jpeg,image/png,image/webp" style="display: none;">

                @if($product->hasThumbnail())
                    <!-- Existing Thumbnail Preview -->
                    <div id="thumbnailPreview" class="text-center py-3">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $product->thumbnail_url }}"
                                 alt="Current Thumbnail"
                                 class="img-fluid rounded mb-2"
                                 style="max-height: 300px; object-fit: cover;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="removeThumbnailBtn">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <p class="text-muted mt-2 mb-2">Current main image</p>
                        <button type="button" class="btn btn-primary btn-sm" id="browseThumbnailBtn">
                            <i class="bi bi-folder2-open"></i> Change Main Image
                        </button>
                    </div>
                @else
                    <!-- No Thumbnail Preview -->
                    <div id="thumbnailPreview" class="text-center py-3">
                        <i class="bi bi-image" style="font-size: 3rem; color: #6c757d;"></i>
                        <p class="text-muted mt-2 mb-2">No image selected</p>
                        <button type="button" class="btn btn-primary btn-sm" id="browseThumbnailBtn">
                            <i class="bi bi-folder2-open"></i> Select Main Image
                        </button>
                    </div>
                @endif

                <small class="text-muted">Recommended: 800x800px | Max: 5MB | Formats: JPG, PNG, WEBP</small>
            </div>
        </div>
    </div>

    <!-- Gallery Images -->
    <div class="mb-4">
        <label class="form-label">Gallery Images (Optional)</label>
        <div class="card">
            <div class="card-body">
                <input type="file" id="galleryInput" name="gallery[]" multiple accept="image/jpg,image/jpeg,image/png,image/webp" style="display: none;">

                <div class="text-center py-3 mb-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="browseGalleryBtn">
                        <i class="bi bi-folder2-open"></i> Add Gallery Images
                    </button>
                    <p class="text-muted small mt-2 mb-0">You can select multiple images at once</p>
                </div>

                <div id="galleryPreview" class="row g-3">
                    @if($product->hasGalleryImages())
                        @foreach($product->images as $image)
                            <div class="col-md-3" data-media-id="{{ $image->id }}">
                                <div class="position-relative">
                                    <img src="{{ $image->url }}"
                                         alt="Gallery Image"
                                         class="img-fluid rounded"
                                         style="width: 100%; height: 150px; object-fit: cover;">
                                    <button type="button"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-gallery-image"
                                            data-media-id="{{ $image->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <!-- New gallery images will be added here -->
                </div>

                <small class="text-muted">Max: 5MB per image | Formats: JPG, PNG, WEBP</small>
            </div>
        </div>
    </div>

    <!-- Hidden fields for deleted media -->
    <div id="deletedMediaContainer"></div>
</div>
