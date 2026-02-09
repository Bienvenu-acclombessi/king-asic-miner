<!-- Create Minable Coin Modal -->
<div class="modal fade" id="createMinableCoinModal" tabindex="-1" aria-labelledby="createMinableCoinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('admin.minable-coins.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createMinableCoinModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Minable Coin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6 mb-3">
                            <label for="create-name" class="form-label">Coin Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create-name" name="name" required>
                            <small class="text-muted">e.g., Bitcoin, Litecoin</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="create-symbol" class="form-label">Symbol <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create-symbol" name="symbol" required maxlength="10" style="text-transform: uppercase;">
                            <small class="text-muted">e.g., BTC, LTC (max 10 characters)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="create-algorithm" class="form-label">Algorithm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create-algorithm" name="algorithm" required>
                            <small class="text-muted">e.g., SHA-256, Scrypt, kHeavyHash</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="create-color" class="form-label">Brand Color</label>
                            <input type="color" class="form-control form-control-color" id="create-color" name="color" value="#000000">
                            <small class="text-muted">Color for UI representation</small>
                        </div>

                        <!-- Mining Parameters -->
                        <div class="col-12"><hr><h6>Mining Parameters (for calculator)</h6></div>

                        <div class="col-md-6 mb-3">
                            <label for="create-difficulty" class="form-label">Network Difficulty</label>
                            <input type="text" class="form-control" id="create-difficulty" name="difficulty">
                            <small class="text-muted">e.g., 1.059e+21 (scientific notation supported)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="create-block_time" class="form-label">Block Time (seconds)</label>
                            <input type="number" class="form-control" id="create-block_time" name="block_time" min="1">
                            <small class="text-muted">e.g., 600 for Bitcoin (10 minutes)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="create-block_reward" class="form-label">Block Reward</label>
                            <input type="number" class="form-control" id="create-block_reward" name="block_reward" step="0.00000001">
                            <small class="text-muted">e.g., 3.125 for Bitcoin</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="create-default_price" class="form-label">Default Price (USD)</label>
                            <input type="number" class="form-control" id="create-default_price" name="default_price" step="0.01" min="0">
                            <small class="text-muted">Default coin price in USD</small>
                        </div>

                        <!-- Logo Upload -->
                        <div class="col-12"><hr><h6>Coin Logo</h6></div>

                        <div class="col-12 mb-3">
                            <label for="create-logo" class="form-label">Logo Image</label>
                            <input type="file" class="form-control" id="create-logo" name="logo" accept="image/*">
                            <small class="text-muted">Recommended: Square image (PNG, SVG preferred). Max 2MB.</small>
                            <div class="mt-2">
                                <img id="create-logo-preview" src="" alt="Logo Preview" style="max-width: 100px; display: none;" class="border rounded">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="create-is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="create-is_active">
                                    <strong>Active</strong> - Coin is visible and can be selected
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Create Coin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
