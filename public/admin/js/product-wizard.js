/**
 * ProductWizard - Main wizard class
 */
class ProductWizard {
    constructor() {
        this.formData = {
            name: '',
            slug: '',
            product_type_id: null,
            brand_id: null,
            status: 'draft',
            short_description: '',
            description: '',
            attributes: {},
            minable_coins: [],
            product_options: [],
            variants: [],
            collections: [],
            tags: [],
            customer_groups: [],
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
            thumbnail: null,
            gallery_images: []
        };

        // Initialize managers
        this.stepManager = new StepManager(this);
        this.variantGenerator = new VariantGenerator(this);
        this.priceCalculator = new PriceCalculator(this);
        this.persistence = new FormPersistence(this);

        // Initialize
        this.init();
    }

    /**
     * Initialize wizard
     */
    init() {
        // Check for saved draft
        this.persistence.cleanOldDrafts();
        if (this.persistence.promptRestore()) {
            this.persistence.restore();
        }

        // Setup event listeners
        this.setupEventListeners();

        // Start auto-save
        this.persistence.startAutoSave();

        // Initial UI update
        this.stepManager.updateNavigationButtons();
    }

    /**
     * Setup all event listeners
     */
    setupEventListeners() {
        // Navigation buttons
        document.getElementById('prevBtn').addEventListener('click', () => this.handlePrev());
        document.getElementById('nextBtn').addEventListener('click', () => this.handleNext());
        document.getElementById('skipBtn').addEventListener('click', () => this.handleSkip());
        document.getElementById('saveDraftBtn').addEventListener('click', () => this.handleSaveDraft());
        document.getElementById('publishBtn').addEventListener('click', () => this.handlePublish());

        // Step 1: Basics
        this.setupStep1Listeners();

        // Step 2: Options
        this.setupStep2Listeners();

        // Step 3: Variants
        this.setupStep3Listeners();

        // Step 4: Pricing (dynamic)

        // Step 5: Media
        this.setupStep5Listeners();

        // Step 6: Finalize
        this.setupStep6Listeners();
    }

    /**
     * Setup Step 1 listeners
     */
    setupStep1Listeners() {
        // Auto-generate slug from name
        const name = document.getElementById('name');
        const slug = document.getElementById('slug');

        name.addEventListener('input', (e) => {
            if (!slug.dataset.manuallyEdited) {
                slug.value = this.slugify(e.target.value);
            }
        });

        slug.addEventListener('input', () => {
            slug.dataset.manuallyEdited = 'true';
        });

        // Product type change
        document.getElementById('product_type_id').addEventListener('change', (e) => {
            this.handleProductTypeChange(e);
        });
    }

    /**
     * Setup Step 2 listeners
     */
    setupStep2Listeners() {
        const optionSelector = document.getElementById('optionSelector');

        optionSelector.addEventListener('change', (e) => {
            if (e.target.value) {
                this.addOption(parseInt(e.target.value));
                e.target.value = '';
            }
        });
    }

    /**
     * Setup Step 3 listeners
     */
    setupStep3Listeners() {
        document.getElementById('generateVariantsBtn')?.addEventListener('click', () => {
            this.generateVariants();
        });

        document.getElementById('addVariantManually')?.addEventListener('click', () => {
            this.variantGenerator.addVariantManually();
            this.renderVariants();
        });

        document.getElementById('applyBulkStock')?.addEventListener('click', () => {
            const stock = document.getElementById('bulkStock').value;
            this.variantGenerator.applyBulkStock(stock);
            this.renderVariants();
        });

        document.getElementById('applySkuPrefix')?.addEventListener('click', () => {
            const prefix = document.getElementById('skuPrefix').value;
            this.variantGenerator.applySkuPrefix(prefix);
            this.renderVariants();
        });

        document.getElementById('enableAllVariants')?.addEventListener('click', () => {
            this.variantGenerator.enableAllVariants();
            this.renderVariants();
        });

        document.getElementById('disableAllVariants')?.addEventListener('click', () => {
            this.variantGenerator.disableAllVariants();
            this.renderVariants();
        });
    }

    /**
     * Setup Step 5 listeners
     */
    setupStep5Listeners() {
        const thumbnailInput = document.getElementById('thumbnailInput');
        const galleryInput = document.getElementById('galleryInput');

        // Thumbnail upload
        const browseThumbnailBtn = document.getElementById('browseThumbnailBtn');
        if (browseThumbnailBtn) {
            browseThumbnailBtn.addEventListener('click', () => {
                thumbnailInput.click();
            });
        }

        thumbnailInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleThumbnailUpload(e.target.files[0]);
            }
            // Reset input pour permettre de sélectionner la même image
            e.target.value = '';
        });

        // Gallery upload
        const browseGalleryBtn = document.getElementById('browseGalleryBtn');
        if (browseGalleryBtn) {
            browseGalleryBtn.addEventListener('click', () => {
                console.log('Gallery button clicked');
                galleryInput.click();
            });
        }

        galleryInput.addEventListener('change', (e) => {
            console.log('Gallery files selected:', e.target.files.length);
            if (e.target.files.length > 0) {
                this.handleGalleryUpload(e.target.files);
            }
            // Reset input pour permettre de sélectionner les mêmes images
            e.target.value = '';
        });
    }

    /**
     * Setup Step 6 listeners
     */
    setupStep6Listeners() {
        // No specific listeners needed, handled by form inputs
    }

    /**
     * Handle previous button
     */
    handlePrev() {
        this.collectCurrentStepData();
        this.stepManager.prevStep();
    }

    /**
     * Handle next button
     */
    async handleNext() {
        this.collectCurrentStepData();

        if (await this.stepManager.validateCurrentStep()) {
            this.stepManager.nextStep();

            // Prepare next step
            this.prepareStep(this.stepManager.currentStep);
        }
    }

    /**
     * Handle skip button
     */
    handleSkip() {
        if (this.stepManager.currentStep === 2) {
            this.formData.product_options = [];
            this.stepManager.nextStep();
            this.prepareStep(this.stepManager.currentStep);
        }
    }

    /**
     * Handle save draft
     */
    async handleSaveDraft() {
        this.collectCurrentStepData();
        this.formData.status = 'draft';
        await this.submitForm();
    }

    /**
     * Handle publish
     */
    async handlePublish() {
        this.collectCurrentStepData();
        this.formData.status = 'published';
        await this.submitForm();
    }

    /**
     * Collect data from current step
     */
    collectCurrentStepData() {
        const step = this.stepManager.currentStep;

        if (step === 1) {
            this.formData.name = document.getElementById('name').value;
            this.formData.slug = document.getElementById('slug').value;
            this.formData.product_type_id = parseInt(document.getElementById('product_type_id').value) || null;
            this.formData.brand_id = parseInt(document.getElementById('brand_id').value) || null;
            this.formData.status = document.getElementById('status').value;
            this.formData.short_description = document.getElementById('short_description').value;
            this.formData.description = document.getElementById('description').value;

            // Collect attributes
            this.formData.attributes = {};
            document.querySelectorAll('.attribute-input').forEach(input => {
                const attributeId = input.dataset.attributeId;
                const attributeHandle = input.dataset.attributeHandle;
                let value;

                if (input.type === 'checkbox') {
                    value = input.checked ? '1' : '0';
                } else {
                    value = input.value;
                }

                // Always add attribute, even if empty (server will filter if needed)
                if (attributeHandle) {
                    this.formData.attributes[attributeHandle] = value || '';
                }
            });

            // Collect minable coins
            this.formData.minable_coins = [];
            document.querySelectorAll('.minable-coin-checkbox:checked').forEach(checkbox => {
                this.formData.minable_coins.push(parseInt(checkbox.value));
            });
        } else if (step === 6) {
            const collectionsSelect = document.getElementById('collections');
            this.formData.collections = Array.from(collectionsSelect.selectedOptions).map(opt => parseInt(opt.value));

            const tagsSelect = document.getElementById('tags');
            this.formData.tags = Array.from(tagsSelect.selectedOptions).map(opt => parseInt(opt.value));

            this.formData.customer_groups = [];
            window.wizardData.customerGroups.forEach(group => {
                const checkbox = document.getElementById(`cg_${group.id}`);
                if (checkbox && checkbox.checked) {
                    this.formData.customer_groups.push(group.id);
                }
            });

            this.formData.meta_title = document.getElementById('meta_title').value;
            this.formData.meta_description = document.getElementById('meta_description').value;
            this.formData.meta_keywords = document.getElementById('meta_keywords').value;
        }

        // Save to localStorage
        this.persistence.save();
    }

    /**
     * Prepare a step when entering it
     */
    prepareStep(step) {
        if (step === 2) {
            // Render options when entering or returning to step 2
            this.renderOptions();
        } else if (step === 3) {
            // Auto-generate default variant if needed
            if (this.formData.variants.length === 0) {
                // Always create at least one default variant
                this.formData.variants = [{
                    sku: this.variantGenerator.generateSku(),
                    option_values: [],
                    stock: 0,
                    enabled: true
                }];
            }
            this.renderVariants();
        } else if (step === 4) {
            // Ensure variants exist before pricing
            if (this.formData.variants.length === 0) {
                alert('Please add at least one variant before setting prices.');
                this.stepManager.prevStep();
                return;
            }
            this.priceCalculator.initializePrices();
            this.renderPricing();
        } else if (step === 5) {
            // Render existing images if any
            this.renderThumbnail();
            this.renderGallery();
        } else if (step === 6) {
            this.renderSummary();
        }
    }

    /**
     * Handle product type change
     */
    handleProductTypeChange(e) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const productType = selectedOption?.dataset.type;

        const infoAlert = document.getElementById('productTypeInfo');
        const infoText = document.getElementById('productTypeInfoText');

        if (productType === 'simple') {
            infoText.textContent = 'Simple products have no options and one variant.';
            infoAlert.style.display = 'block';
        } else if (productType === 'variable') {
            infoText.textContent = 'Variable products have options that create multiple variants (e.g., Size, Color).';
            infoAlert.style.display = 'block';
        } else if (productType === 'add-ons') {
            infoText.textContent = 'Add-on products have options added to cart without creating variants (e.g., Warranty, Installation).';
            infoAlert.style.display = 'block';
        } else {
            infoAlert.style.display = 'none';
        }
    }

    /**
     * Check if product is simple
     */
    isSimpleProduct() {
        const productTypeSelect = document.getElementById('product_type_id');
        const selectedOption = productTypeSelect.options[productTypeSelect.selectedIndex];
        return selectedOption?.dataset.type === 'simple';
    }

    /**
     * Add an option
     */
    addOption(optionId) {
        // Check if already added
        if (this.formData.product_options.find(opt => opt.option_id === optionId)) {
            alert('This option is already added');
            return;
        }

        const option = window.wizardData.productOptions.find(opt => opt.id === optionId);
        if (!option) return;

        const isVariable = !this.isSimpleProduct();

        this.formData.product_options.push({
            option_id: optionId,
            display_type: 'select',
            required: false,
            affects_price: isVariable,
            affects_stock: isVariable,
            position: this.formData.product_options.length + 1
        });

        this.renderOptions();
    }

    /**
     * Remove an option
     */
    removeOption(optionId) {
        this.formData.product_options = this.formData.product_options.filter(
            opt => opt.option_id !== optionId
        );
        this.renderOptions();
    }

    /**
     * Update option configuration
     */
    updateOption(optionId, config) {
        const option = this.formData.product_options.find(opt => opt.option_id === optionId);
        if (option) {
            Object.assign(option, config);
        }
    }

    /**
     * Render options list
     */
    renderOptions() {
        const container = document.getElementById('optionsContainer');
        const noOptionsAlert = document.getElementById('noOptionsAlert');

        if (this.formData.product_options.length === 0) {
            container.innerHTML = '';
            noOptionsAlert.style.display = 'block';
            return;
        }

        noOptionsAlert.style.display = 'none';

        let html = '';

        this.formData.product_options.forEach(optConfig => {
            const option = window.wizardData.productOptions.find(opt => opt.id === optConfig.option_id);
            if (!option) return;

            // Extract name from multilingual object if needed
            let rawName = option.label || option.name || option.attribute_data?.name;
            const optionName = (typeof rawName === 'object' && rawName !== null)
                ? (rawName.en || rawName.fr || Object.values(rawName)[0] || '')
                : (rawName || 'Option #' + option.id);

            html += `
                <div class="option-card" data-option-id="${option.id}">
                    <div class="option-card-header">
                        <h6 class="option-card-title">${optionName}</h6>
                        <button type="button" class="btn btn-sm btn-danger" onclick="wizard.removeOption(${option.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label small">Display Type</label>
                            <select class="form-select form-select-sm" onchange="wizard.updateOption(${option.id}, {display_type: this.value})">
                                <option value="select" ${optConfig.display_type === 'select' ? 'selected' : ''}>Dropdown</option>
                                <option value="radio" ${optConfig.display_type === 'radio' ? 'selected' : ''}>Radio</option>
                                <option value="button" ${optConfig.display_type === 'button' ? 'selected' : ''}>Button</option>
                                <option value="color" ${optConfig.display_type === 'color' ? 'selected' : ''}>Color</option>
                                <option value="image" ${optConfig.display_type === 'image' ? 'selected' : ''}>Image</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Required</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" ${optConfig.required ? 'checked' : ''}
                                       onchange="wizard.updateOption(${option.id}, {required: this.checked})">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Affects Price</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" ${optConfig.affects_price ? 'checked' : ''}
                                       onchange="wizard.updateOption(${option.id}, {affects_price: this.checked})">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Affects Stock</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" ${optConfig.affects_stock ? 'checked' : ''}
                                       onchange="wizard.updateOption(${option.id}, {affects_stock: this.checked})">
                            </div>
                        </div>
                    </div>
                    <div class="option-values-grid">
                        ${option.values.map(val => {
                            // Extract value name from multilingual object if needed
                            let rawValName = val.name || val.attribute_data?.name;
                            const valName = (typeof rawValName === 'object' && rawValName !== null)
                                ? (rawValName.en || rawValName.fr || Object.values(rawValName)[0] || '')
                                : (rawValName || 'Value #' + val.id);
                            return `<span class="option-value-badge">${valName}</span>`;
                        }).join('')}
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    /**
     * Generate variants from options
     */
    generateVariants() {
        if (confirm('This will replace existing variants. Continue?')) {
            this.formData.variants = this.variantGenerator.generateVariants();
            this.renderVariants();

            // Show success message
            const variantCount = this.formData.variants.length;
            alert(`Generated ${variantCount} variant${variantCount !== 1 ? 's' : ''} successfully!`);
        }
    }

    /**
     * Render variants table
     */
    renderVariants() {
        const tbody = document.getElementById('variantsTableBody');
        const noVariantsMsg = document.getElementById('noVariantsMessage');
        const variantControls = document.getElementById('variantControls');
        const bulkActions = document.getElementById('bulkActions');

        if (this.formData.variants.length === 0) {
            tbody.innerHTML = '';
            noVariantsMsg.style.display = 'block';
            variantControls.style.display = 'none';
            bulkActions.style.display = 'none';
            return;
        }

        noVariantsMsg.style.display = 'none';

        // Show variant controls for variable products
        if (!this.isSimpleProduct() && this.formData.product_options.length > 0) {
            variantControls.style.display = 'block';
        } else {
            variantControls.style.display = 'none';
        }

        bulkActions.style.display = 'block';

        let html = '';

        this.formData.variants.forEach((variant, index) => {
            const displayName = this.variantGenerator.getVariantDisplayName(variant);

            html += `
                <tr>
                    <td>
                        <div class="variant-name">${displayName}</div>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm variant-sku-input"
                               value="${variant.sku}"
                               onchange="wizard.updateVariantField(${index}, 'sku', this.value)">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" min="0"
                               value="${variant.stock}"
                               onchange="wizard.updateVariantField(${index}, 'stock', parseInt(this.value))">
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" ${variant.enabled ? 'checked' : ''}
                                   onchange="wizard.updateVariantField(${index}, 'enabled', this.checked)">
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="wizard.removeVariant(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    /**
     * Update variant field
     */
    updateVariantField(index, field, value) {
        if (this.formData.variants[index]) {
            this.formData.variants[index][field] = value;
        }
    }

    /**
     * Remove variant
     */
    removeVariant(index) {
        if (confirm('Remove this variant?')) {
            this.variantGenerator.removeVariant(index);
            this.renderVariants();
        }
    }

    /**
     * Render pricing section
     */
    renderPricing() {
        const container = document.getElementById('pricingContainer');
        const noPricingMsg = document.getElementById('noPricingMessage');

        if (this.formData.variants.length === 0) {
            container.innerHTML = '';
            noPricingMsg.style.display = 'block';
            return;
        }

        noPricingMsg.style.display = 'none';

        let html = '';

        this.formData.variants.forEach((variant, vIndex) => {
            const displayName = this.variantGenerator.getVariantDisplayName(variant);
            const priceValue = variant.price ? (variant.price / 100).toFixed(2) : '0.00';
            const compareValue = variant.compare_price ? (variant.compare_price / 100).toFixed(2) : '';
            const minQty = variant.min_quantity || 1;

            html += `
                <div class="pricing-variant-section">
                    <div class="pricing-variant-header">
                        <i class="bi bi-tag me-2"></i>${displayName} (${variant.sku})
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" step="0.01" min="0"
                                       placeholder="0.00" value="${priceValue}" required
                                       onchange="wizard.updateVariantPrice(${vIndex}, 'price', this.value)">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Compare Price ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" step="0.01" min="0"
                                       placeholder="0.00" value="${compareValue}"
                                       onchange="wizard.updateVariantPrice(${vIndex}, 'compare_price', this.value)">
                            </div>
                            <small class="text-muted">Original price for sales</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Min Quantity</label>
                            <input type="number" class="form-control" min="1" value="${minQty}"
                                   onchange="wizard.updateVariantPrice(${vIndex}, 'min_quantity', this.value)">
                            <small class="text-muted">Minimum order quantity</small>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    /**
     * Update variant price (simplified without customer groups)
     */
    updateVariantPrice(variantIndex, field, value) {
        if (!this.formData.variants[variantIndex]) return;

        if (field === 'price' || field === 'compare_price') {
            value = this.priceCalculator.parsePrice(value);
        } else if (field === 'min_quantity') {
            value = parseInt(value) || 1;
        }

        this.formData.variants[variantIndex][field] = value;
    }


    /**
     * Handle thumbnail upload
     */
    handleThumbnailUpload(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (!validTypes.includes(file.type)) {
            alert(`${file.name} is not a valid image format`);
            return;
        }

        if (file.size > maxSize) {
            alert(`${file.name} is too large (max 5MB)`);
            return;
        }

        this.formData.thumbnail = file;
        this.renderThumbnail();
    }

    /**
     * Handle gallery upload
     */
    handleGalleryUpload(files) {
        console.log('handleGalleryUpload called with files:', files);

        // Ensure gallery_images is initialized
        if (!this.formData.gallery_images) {
            this.formData.gallery_images = [];
        }

        const validFiles = Array.from(files).filter(file => {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!validTypes.includes(file.type)) {
                alert(`${file.name} is not a valid image format`);
                return false;
            }

            if (file.size > maxSize) {
                alert(`${file.name} is too large (max 5MB)`);
                return false;
            }

            return true;
        });

        console.log('Valid files:', validFiles.length);

        validFiles.forEach(file => {
            this.formData.gallery_images.push(file);
        });

        console.log('Total gallery images now:', this.formData.gallery_images.length);

        this.renderGallery();
    }

    /**
     * Render thumbnail
     */
    renderThumbnail() {
        const preview = document.getElementById('thumbnailPreview');

        if (!this.formData.thumbnail) {
            preview.innerHTML = `
                <i class="bi bi-image" style="font-size: 3rem; color: #6c757d;"></i>
                <p class="text-muted mt-2 mb-2">No image selected</p>
                <button type="button" class="btn btn-primary btn-sm" id="browseThumbnailBtn">
                    <i class="bi bi-folder2-open"></i> Select Main Image
                </button>
            `;
            document.getElementById('browseThumbnailBtn').addEventListener('click', () => {
                document.getElementById('thumbnailInput').click();
            });
            return;
        }

        const url = URL.createObjectURL(this.formData.thumbnail);

        preview.innerHTML = `
            <div class="position-relative d-inline-block">
                <img src="${url}" alt="Thumbnail" style="max-width: 300px; max-height: 300px; border-radius: 0.375rem;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="wizard.removeThumbnail()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <p class="text-muted small mt-2 mb-0">${this.formData.thumbnail.name}</p>
        `;
    }

    /**
     * Render gallery
     */
    renderGallery() {
        // Ensure gallery_images is initialized
        if (!this.formData.gallery_images) {
            this.formData.gallery_images = [];
        }

        console.log('renderGallery called, gallery_images count:', this.formData.gallery_images.length);

        const gallery = document.getElementById('galleryPreview');

        if (!gallery) {
            console.error('Gallery preview element not found!');
            return;
        }

        if (this.formData.gallery_images.length === 0) {
            gallery.innerHTML = '<div class="col-12"><p class="text-muted text-center mb-0">No gallery images added yet</p></div>';
            return;
        }

        let html = '';

        this.formData.gallery_images.forEach((file, index) => {
            const url = URL.createObjectURL(file);

            html += `
                <div class="col-md-3 col-lg-2">
                    <div class="position-relative">
                        <img src="${url}" alt="${file.name}" class="img-fluid rounded" style="width: 100%; height: 120px; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="wizard.removeGalleryImage(${index})">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <small class="text-muted d-block text-truncate mt-1">${file.name}</small>
                </div>
            `;
        });

        gallery.innerHTML = html;
        console.log('Gallery rendered with', this.formData.gallery_images.length, 'images');
    }

    /**
     * Remove thumbnail
     */
    removeThumbnail() {
        this.formData.thumbnail = null;
        this.renderThumbnail();
    }

    /**
     * Remove gallery image
     */
    removeGalleryImage(index) {
        if (!this.formData.gallery_images) {
            this.formData.gallery_images = [];
        }
        this.formData.gallery_images.splice(index, 1);
        this.renderGallery();
    }

    /**
     * Render summary
     */
    renderSummary() {
        const container = document.getElementById('productSummary');

        const productType = window.wizardData.productTypes.find(
            t => t.id === this.formData.product_type_id
        );
        const brand = window.wizardData.brands.find(
            b => b.id === this.formData.brand_id
        );

        const productTypeName = productType?.name || productType?.attribute_data?.name || 'N/A';
        const brandName = brand?.name || brand?.attribute_data?.name || 'N/A';

        // Get collection names
        const collections = this.formData.collections || [];
        const collectionNames = collections.map(collId => {
            const collection = window.wizardData.collections?.find(c => c.id === collId);
            if (collection) {
                const name = collection.name;
                return (typeof name === 'object' && name !== null)
                    ? (name.en || name.fr || Object.values(name)[0] || `Collection #${collId}`)
                    : (name || `Collection #${collId}`);
            }
            return `Collection #${collId}`;
        });

        // Get tag names
        const tags = this.formData.tags || [];
        const tagNames = tags.map(tagId => {
            const tag = window.wizardData.tags?.find(t => t.id === tagId);
            if (tag) {
                // Tags use 'value' field instead of 'name'
                const value = tag.value;
                return (typeof value === 'object' && value !== null)
                    ? (value.en || value.fr || Object.values(value)[0] || `Tag #${tagId}`)
                    : (value || `Tag #${tagId}`);
            }
            return `Tag #${tagId}`;
        });

        let html = `
            <div class="summary-item">
                <div class="summary-label">Product Name</div>
                <div class="summary-value">${this.formData.name || 'N/A'}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Product Type</div>
                <div class="summary-value">${productTypeName}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Brand</div>
                <div class="summary-value">${brandName || 'N/A'}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Status</div>
                <div class="summary-value">${this.formData.status}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Options</div>
                <div class="summary-value">${(this.formData.product_options || []).length} option(s)</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Variants</div>
                <div class="summary-value">${(this.formData.variants || []).length} variant(s)</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Images</div>
                <div class="summary-value">
                    ${this.formData.thumbnail ? '1 thumbnail' : 'No thumbnail'}
                    ${(this.formData.gallery_images && this.formData.gallery_images.length > 0) ? `<br>+ ${this.formData.gallery_images.length} gallery image(s)` : ''}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Collections</div>
                <div class="summary-value">
                    ${collections.length > 0 ? collectionNames.join(', ') : 'None selected'}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Tags</div>
                <div class="summary-value">
                    ${tags.length > 0 ? tagNames.join(', ') : 'None selected'}
                </div>
            </div>
        `;

        container.innerHTML = html;
    }

    /**
     * Submit form
     */
    async submitForm() {
        // Show loading
        const publishBtn = document.getElementById('publishBtn');
        const saveDraftBtn = document.getElementById('saveDraftBtn');
        publishBtn.classList.add('btn-loading');
        saveDraftBtn.classList.add('btn-loading');

        // Prepare form data
        const formData = new FormData();

        // Add all data
        formData.append('name', this.formData.name);
        formData.append('slug', this.formData.slug);
        formData.append('product_type_id', this.formData.product_type_id);
        if (this.formData.brand_id) {
            formData.append('brand_id', this.formData.brand_id);
        }
        formData.append('status', this.formData.status);
        formData.append('short_description', this.formData.short_description || '');
        formData.append('description', this.formData.description || '');

        // Attributes
        if (this.formData.attributes) {
            Object.keys(this.formData.attributes).forEach(key => {
                formData.append(`attributes[${key}]`, this.formData.attributes[key]);
            });
        }

        // Product options
        this.formData.product_options.forEach((opt, i) => {
            Object.keys(opt).forEach(key => {
                let value = opt[key];
                // Convert booleans to 1/0 for Laravel validation
                if (key === 'required' || key === 'affects_price' || key === 'affects_stock') {
                    value = value ? 1 : 0;
                }
                formData.append(`product_options[${i}][${key}]`, value);
            });
        });

        // Variants
        this.formData.variants.forEach((variant, i) => {
            formData.append(`variants[${i}][sku]`, variant.sku);
            formData.append(`variants[${i}][stock]`, variant.stock);
            formData.append(`variants[${i}][enabled]`, variant.enabled ? 1 : 0);
            formData.append(`variants[${i}][price]`, variant.price || 0);
            formData.append(`variants[${i}][compare_price]`, variant.compare_price || '');
            formData.append(`variants[${i}][min_quantity]`, variant.min_quantity || 1);

            variant.option_values?.forEach((valId, j) => {
                formData.append(`variants[${i}][option_values][${j}]`, valId);
            });
        });

        // Images
        if (this.formData.thumbnail) {
            formData.append('thumbnail', this.formData.thumbnail);
        }

        if (this.formData.gallery_images && this.formData.gallery_images.length > 0) {
            this.formData.gallery_images.forEach(file => {
                formData.append('gallery[]', file);
            });
        }

        // Collections
        this.formData.collections.forEach(id => {
            formData.append('collections[]', id);
        });

        // Tags
        this.formData.tags.forEach(id => {
            formData.append('tags[]', id);
        });

        // Minable Coins
        this.formData.minable_coins.forEach(id => {
            formData.append('minable_coins[]', id);
        });

        // Customer groups
        this.formData.customer_groups.forEach(id => {
            formData.append('customer_groups[]', id);
        });

        // SEO
        formData.append('meta_title', this.formData.meta_title || '');
        formData.append('meta_description', this.formData.meta_description || '');
        formData.append('meta_keywords', this.formData.meta_keywords || '');

        try {
            const response = await fetch(window.wizardData.storeUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': window.wizardData.csrfToken
                }
            });

            const result = await response.json();

            if (result.success) {
                // Clear draft
                this.persistence.clear();
                this.persistence.stopAutoSave();

                // Redirect
                window.location.href = result.redirect;
            } else {
                alert('Error: ' + (result.message || 'Unknown error'));
                console.error(result.errors);
            }
        } catch (error) {
            alert('Error submitting form: ' + error.message);
            console.error(error);
        } finally {
            publishBtn.classList.remove('btn-loading');
            saveDraftBtn.classList.remove('btn-loading');
        }
    }

    /**
     * Slugify a string
     */
    slugify(text) {
        return text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-');
    }
}

// Initialize wizard when DOM is ready
let wizard;
document.addEventListener('DOMContentLoaded', () => {
    wizard = new ProductWizard();
});
