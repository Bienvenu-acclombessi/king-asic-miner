<div class="modal fade" id="createCouponModal" tabindex="-1" aria-labelledby="createCouponModalLabel" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCouponModalLabel">Create New Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCouponForm">
                @csrf
                <div class="modal-body">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">
                                Général
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="restrictions-tab" data-bs-toggle="tab" data-bs-target="#restrictions" type="button">
                                Restrictions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button">
                                Produits/Collections
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Coupon Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" name="coupon" class="form-control" style="text-transform: uppercase;" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                                <option value="free_shipping">Free Shipping</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3" id="discountValueGroup">
                            <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="discount_value" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>

                            <h6 class="mb-3 mt-4">Validity Period</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Valid From</label>
                                    <input type="datetime-local" name="starts_at" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Valid To</label>
                                    <input type="datetime-local" name="ends_at" class="form-control">
                                </div>
                            </div>

                            <h6 class="mb-3 mt-4">Options</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="individual_use" value="1">
                                        <label class="form-check-label">Individual use only</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="exclude_sale_items" value="1">
                                        <label class="form-check-label">Exclude sale items</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="free_shipping" value="1">
                                        <label class="form-check-label">Free shipping</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Restrictions Tab -->
                        <div class="tab-pane fade" id="restrictions" role="tabpanel">
                            <h6 class="mb-3">Order Conditions</h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Minimum Order Amount</label>
                                    <input type="number" step="0.01" name="min_order_amount" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Maximum Order Amount</label>
                                    <input type="number" step="0.01" name="max_order_amount" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Minimum Quantity</label>
                                    <input type="number" name="min_qty" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Maximum Discount Amount</label>
                                    <input type="number" step="0.01" name="max_discount_amount" class="form-control">
                                    <small class="text-muted">For percentage discounts</small>
                                </div>
                            </div>

                            <h6 class="mb-3 mt-4">Usage Limits</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Usage Limit Per Coupon</label>
                                    <input type="number" name="max_uses" class="form-control" placeholder="Unlimited">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Usage Limit Per User</label>
                                    <input type="number" name="max_uses_per_user" class="form-control" placeholder="Unlimited">
                                </div>
                            </div>

                            <h6 class="mb-3 mt-4">Email Restrictions</h6>
                            <div class="mb-3">
                                <label class="form-label">Allowed Emails</label>
                                <textarea name="allowed_emails" class="form-control" rows="2" placeholder="email1@example.com, email2@example.com"></textarea>
                                <small class="text-muted">Comma-separated email addresses. Leave empty for all users.</small>
                            </div>
                        </div>

                        <!-- Products/Collections Tab -->
                        <div class="tab-pane fade" id="products" role="tabpanel">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Note:</strong> Laissez vide pour appliquer à tous les produits.
                            </div>

                            <h6 class="mb-3">Products</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Included Products</label>
                                    <select name="included_products[]" class="form-select select2-products" multiple>
                                        <!-- Options loaded via AJAX -->
                                    </select>
                                    <small class="text-muted">Coupon applies only to these products</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Excluded Products</label>
                                    <select name="excluded_products[]" class="form-select select2-products" multiple>
                                        <!-- Options loaded via AJAX -->
                                    </select>
                                    <small class="text-muted">Coupon won't apply to these products</small>
                                </div>
                            </div>

                            <h6 class="mb-3 mt-4">Collections/Categories</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Included Collections</label>
                                    <select name="included_collections[]" class="form-select select2-collections" multiple>
                                        <!-- Options loaded via AJAX -->
                                    </select>
                                    <small class="text-muted">Coupon applies to products in these collections</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Excluded Collections</label>
                                    <select name="excluded_collections[]" class="form-select select2-collections" multiple>
                                        <!-- Options loaded via AJAX -->
                                    </select>
                                    <small class="text-muted">Coupon won't apply to these collections</small>
                                </div>
                            </div>

                            <h6 class="mb-3 mt-4">Brands</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Included Brands</label>
                                    <select name="included_brands[]" class="form-select select2-brands" multiple>
                                        <!-- Options loaded via AJAX -->
                                    </select>
                                    <small class="text-muted">Coupon applies to products of these brands</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Excluded Brands</label>
                                    <select name="excluded_brands[]" class="form-select select2-brands" multiple>
                                        <!-- Options loaded via AJAX -->
                                    </select>
                                    <small class="text-muted">Coupon won't apply to these brands</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                        Create Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
