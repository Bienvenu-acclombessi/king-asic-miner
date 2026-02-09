<!-- Edit Minable Coin Modal -->
<div class="modal fade" id="editMinableCoinModal" tabindex="-1" aria-labelledby="editMinableCoinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="edit-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-coin-id" name="coin_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="editMinableCoinModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit Minable Coin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6 mb-3">
                            <label for="edit-name" class="form-label">Coin Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                            <small class="text-muted">e.g., Bitcoin, Litecoin</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit-symbol" class="form-label">Symbol <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-symbol" name="symbol" required maxlength="10" style="text-transform: uppercase;">
                            <small class="text-muted">e.g., BTC, LTC (max 10 characters)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit-algorithm" class="form-label">Algorithm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-algorithm" name="algorithm" required>
                            <small class="text-muted">e.g., SHA-256, Scrypt, kHeavyHash</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit-color" class="form-label">Brand Color</label>
                            <input type="color" class="form-control form-control-color" id="edit-color" name="color">
                            <small class="text-muted">Color for UI representation</small>
                        </div>

                        <!-- Mining Parameters -->
                        <div class="col-12"><hr><h6>Mining Parameters (for calculator)</h6></div>

                        <div class="col-md-6 mb-3">
                            <label for="edit-difficulty" class="form-label">Network Difficulty</label>
                            <input type="text" class="form-control" id="edit-difficulty" name="difficulty">
                            <small class="text-muted">e.g., 1.059e+21 (scientific notation supported)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit-block_time" class="form-label">Block Time (seconds)</label>
                            <input type="number" class="form-control" id="edit-block_time" name="block_time" min="1">
                            <small class="text-muted">e.g., 600 for Bitcoin (10 minutes)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit-block_reward" class="form-label">Block Reward</label>
                            <input type="number" class="form-control" id="edit-block_reward" name="block_reward" step="0.00000001">
                            <small class="text-muted">e.g., 3.125 for Bitcoin</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit-default_price" class="form-label">Default Price (USD)</label>
                            <input type="number" class="form-control" id="edit-default_price" name="default_price" step="0.01" min="0">
                            <small class="text-muted">Default coin price in USD</small>
                        </div>

                        <!-- Logo Upload -->
                        <div class="col-12"><hr><h6>Coin Logo</h6></div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Current Logo</label>
                            <div>
                                <img id="current-logo" src="" alt="Current Logo" style="max-width: 100px; display: none;" class="border rounded">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="edit-logo" class="form-label">New Logo Image (optional)</label>
                            <input type="file" class="form-control" id="edit-logo" name="logo" accept="image/*">
                            <small class="text-muted">Recommended: Square image (PNG, SVG preferred). Max 2MB. Leave empty to keep current logo.</small>
                            <div class="mt-2">
                                <img id="edit-logo-preview" src="" alt="New Logo Preview" style="max-width: 100px; display: none;" class="border rounded">
                            </div>
                        </div>

                        <div class="col-12 mb-3" id="remove-logo-wrapper" style="display: none;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit-remove_logo" name="remove_logo" value="1">
                                <label class="form-check-label" for="edit-remove_logo">
                                    Remove current logo
                                </label>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit-is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit-is_active">
                                    <strong>Active</strong> - Coin is visible and can be selected
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-1"></i>Update Coin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Update form action dynamically when modal is shown
$('#editMinableCoinModal').on('show.bs.modal', function() {
    const coinId = $('#edit-coin-id').val();
    $('#edit-form').attr('action', `/king-admin/minable-coins/${coinId}`);
});
</script>
