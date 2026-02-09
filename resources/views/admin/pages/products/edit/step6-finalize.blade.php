<div class="step-content">
    <h4 class="mb-3">Finalize & Publish</h4>
    <p class="text-muted mb-4">Complete your product setup and review before publishing</p>

    <div class="row">
        <!-- Collections -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Collections (Categories)</label>
            <select class="form-select" name="collections[]" id="collections" multiple size="8">
                @foreach($collections as $collection)
                    @php
                        $collectionName = $collection->name ?? (is_array($collection->attribute_data) ? ($collection->attribute_data['name'] ?? '') : '');
                        $collectionName = is_array($collectionName) ? ($collectionName['en'] ?? $collectionName[0] ?? '') : $collectionName;
                        $isSelected = $product->collections && $product->collections->contains($collection->id);
                    @endphp
                    <option value="{{ $collection->id }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $collectionName ?: 'Collection #' . $collection->id }}
                    </option>
                    @foreach($collection->children as $child)
                        @php
                            $childName = $child->name ?? (is_array($child->attribute_data) ? ($child->attribute_data['name'] ?? '') : '');
                            $childName = is_array($childName) ? ($childName['en'] ?? $childName[0] ?? '') : $childName;
                            $isChildSelected = $product->collections && $product->collections->contains($child->id);
                        @endphp
                        <option value="{{ $child->id }}" {{ $isChildSelected ? 'selected' : '' }}>
                            &nbsp;&nbsp;&nbsp;↳ {{ $childName ?: 'Collection #' . $child->id }}
                        </option>
                    @endforeach
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple</small>
        </div>

        <!-- Tags -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Tags</label>
            <select class="form-select" name="tags[]" id="tags" multiple size="8">
                @foreach($tags as $tag)
                    @php
                        $tagName = $tag->name ?? (is_array($tag->attribute_data) ? ($tag->attribute_data['name'] ?? '') : '');
                        $tagName = is_array($tagName) ? ($tagName['en'] ?? $tagName[0] ?? '') : $tagName;
                        $isTagSelected = $product->tags && $product->tags->contains($tag->id);
                    @endphp
                    <option value="{{ $tag->id }}" {{ $isTagSelected ? 'selected' : '' }}>
                        {{ $tagName ?: 'Tag #' . $tag->id }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple</small>
        </div>

        <!-- Customer Groups (Visibility) -->
        <div class="col-12 mb-4">
            <label class="form-label">Visible to Customer Groups</label>
            <div class="row">
                @foreach($customerGroups as $group)
                    @php
                        $groupName = $group->name ?? (is_array($group->attribute_data) ? ($group->attribute_data['name'] ?? '') : '');
                        $groupName = is_array($groupName) ? ($groupName['en'] ?? $groupName[0] ?? '') : $groupName;
                        $isGroupSelected = $product->customerGroups && $product->customerGroups->contains($group->id);
                    @endphp
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="customer_groups[]"
                                   value="{{ $group->id }}" id="cg_{{ $group->id }}"
                                   {{ $isGroupSelected ? 'checked' : '' }}>
                            <label class="form-check-label" for="cg_{{ $group->id }}">
                                {{ $groupName ?: 'Group #' . $group->id }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SEO Section -->
        <div class="col-12 mb-3">
            <h5 class="border-bottom pb-2 mb-3">SEO Settings</h5>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Meta Title</label>
            <input type="text" class="form-control" name="meta_title" id="meta_title"
                   value="{{ old('meta_title', $productData['meta_title'] ?? '') }}" maxlength="255">
            <small class="form-text text-muted">Recommended: 50-60 characters</small>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Meta Description</label>
            <textarea class="form-control" name="meta_description" id="meta_description"
                      rows="3" maxlength="160">{{ old('meta_description', $productData['meta_description'] ?? '') }}</textarea>
            <small class="form-text text-muted">Recommended: 150-160 characters</small>
        </div>

        <div class="col-12 mb-4">
            <label class="form-label">Meta Keywords</label>
            <input type="text" class="form-control" name="meta_keywords" id="meta_keywords"
                   value="{{ old('meta_keywords', $productData['meta_keywords'] ?? '') }}">
            <small class="form-text text-muted">Comma-separated keywords</small>
        </div>

        <!-- Review Summary -->
        <div class="col-12">
            <h5 class="border-bottom pb-2 mb-3">Review Your Product</h5>
            <div class="card">
                <div class="card-body">
                    <div id="productSummary">
                        <!-- Summary will be dynamically generated -->
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Product Name:</strong> {{ $productData['name'] ?? 'N/A' }}</p>
                                <p><strong>Status:</strong> <span class="badge bg-{{ $product->status == 'published' ? 'success' : 'secondary' }}">{{ ucfirst($product->status) }}</span></p>
                                <p><strong>SKU:</strong> {{ $productData['variants'][0]['sku'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                @php
                                    $typeName = $product->productType->name ?? '';
                                    $typeName = is_array($typeName) ? ($typeName['en'] ?? $typeName[0] ?? 'N/A') : ($typeName ?: 'N/A');
                                    $brandName = $product->brand->name ?? '';
                                    $brandName = is_array($brandName) ? ($brandName['en'] ?? $brandName[0] ?? 'N/A') : ($brandName ?: 'N/A');
                                @endphp
                                <p><strong>Type:</strong> {{ $typeName }}</p>
                                <p><strong>Brand:</strong> {{ $brandName }}</p>
                                <p><strong>Featured:</strong> {{ $product->is_featured ? 'Yes' : 'No' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
