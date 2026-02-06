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
                    @endphp
                    <option value="{{ $collection->id }}">
                        {{ $collectionName ?: 'Collection #' . $collection->id }}
                    </option>
                    @foreach($collection->children as $child)
                        @php
                            $childName = $child->name ?? (is_array($child->attribute_data) ? ($child->attribute_data['name'] ?? '') : '');
                            $childName = is_array($childName) ? ($childName['en'] ?? $childName[0] ?? '') : $childName;
                        @endphp
                        <option value="{{ $child->id }}">
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
                    @endphp
                    <option value="{{ $tag->id }}">{{ $tagName ?: 'Tag #' . $tag->id }}</option>
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
                    @endphp
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="customer_groups[]"
                                   value="{{ $group->id }}" id="cg_{{ $group->id }}" checked>
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
            <input type="text" class="form-control" name="meta_title" id="meta_title" maxlength="255">
            <small class="form-text text-muted">Recommended: 50-60 characters</small>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Meta Description</label>
            <textarea class="form-control" name="meta_description" id="meta_description" rows="3" maxlength="160"></textarea>
            <small class="form-text text-muted">Recommended: 150-160 characters</small>
        </div>

        <div class="col-12 mb-4">
            <label class="form-label">Meta Keywords</label>
            <input type="text" class="form-control" name="meta_keywords" id="meta_keywords">
            <small class="form-text text-muted">Comma-separated keywords</small>
        </div>

        <!-- Review Summary -->
        <div class="col-12">
            <h5 class="border-bottom pb-2 mb-3">Review Your Product</h5>
            <div class="card">
                <div class="card-body">
                    <div id="productSummary">
                        <!-- Summary will be dynamically generated -->
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-hourglass-split"></i> Summary will appear here
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
