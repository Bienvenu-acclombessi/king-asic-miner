<div class="modal fade" id="createShippingMethodModal" tabindex="-1" aria-labelledby="createShippingMethodModalLabel" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createShippingMethodModalLabel">Create New Shipping Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createShippingMethodForm">
                @csrf
                <div class="modal-body">
                    <!-- Basic Information -->
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Method Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Standard Shipping" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief description of this shipping method"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Pricing -->
                    <h6 class="mb-3 mt-4">Pricing</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price Type <span class="text-danger">*</span></label>
                            <select name="price_type" class="form-select" required>
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage of Cart Total</option>
                                <option value="free">Free Shipping</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3" id="priceGroup">
                            <label class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                            <small class="text-muted">For percentage, enter % value (e.g., 5 for 5%)</small>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Conditions -->
                    <h6 class="mb-3 mt-4">Order Conditions</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimum Order Amount</label>
                            <input type="number" step="0.01" name="min_order_amount" class="form-control" placeholder="0.00">
                            <small class="text-muted">Leave empty for no minimum</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maximum Order Amount</label>
                            <input type="number" step="0.01" name="max_order_amount" class="form-control" placeholder="0.00">
                            <small class="text-muted">Leave empty for no maximum</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maximum Weight (kg)</label>
                            <input type="number" step="0.01" name="max_weight" class="form-control" placeholder="0.00">
                            <small class="text-muted">Leave empty for no weight limit</small>
                        </div>
                    </div>

                    <!-- Delivery Time -->
                    <h6 class="mb-3 mt-4">Estimated Delivery Time</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimum Days</label>
                            <input type="number" name="estimated_days_min" class="form-control" placeholder="e.g., 3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Maximum Days</label>
                            <input type="number" name="estimated_days_max" class="form-control" placeholder="e.g., 5">
                        </div>
                    </div>
                    <small class="text-muted">Will display as "3-5 days". Leave empty if no estimate available.</small>

                    <!-- Display Settings -->
                    <h6 class="mb-3 mt-4">Display Settings</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" placeholder="0">
                            <small class="text-muted">Lower numbers appear first</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                        Create Shipping Method
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
