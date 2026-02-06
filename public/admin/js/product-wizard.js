/**
 * ProductWizard - Main wizard class
 */
class ProductWizard {
    constructor() {
        this.formData = {
            name: { en: '', fr: '' },
            slug: '',
            product_type_id: null,
            brand_id: null,
            status: 'draft',
            short_description: { en: '', fr: '' },
            description: { en: '', fr: '' },
            product_options: [],
            variants: [],
            collections: [],
            tags: [],
            customer_groups: [],
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
            images: []
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
        const nameEn = document.getElementById('name_en');
        const slug = document.getElementById('slug');

        nameEn.addEventListener('input', (e) => {
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
        const uploadArea = document.getElementById('uploadArea');
        const imageInput = document.getElementById('imageInput');
        const browseBtn = document.getElementById('browseImagesBtn');

        // Click to browse
        browseBtn.addEventListener('click', () => imageInput.click());
        uploadArea.addEventListener('click', () => imageInput.click());

        // File selection
        imageInput.addEventListener('change', (e) => {
            this.handleImageUpload(e.target.files);
        });

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            this.handleImageUpload(e.dataTransfer.files);
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
            this.formData.name.en = document.getElementById('name_en').value;
            this.formData.name.fr = document.getElementById('name_fr').value;
            this.formData.slug = document.getElementById('slug').value;
            this.formData.product_type_id = parseInt(document.getElementById('product_type_id').value) || null;
            this.formData.brand_id = parseInt(document.getElementById('brand_id').value) || null;
            this.formData.status = document.getElementById('status').value;
            this.formData.short_description.en = document.getElementById('short_description_en').value;
            this.formData.short_description.fr = document.getElementById('short_description_fr').value;
            this.formData.description.en = document.getElementById('description_en').value;
            this.formData.description.fr = document.getElementById('description_fr').value;
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
        if (step === 3) {
            // Auto-generate default variant if needed
            if (this.formData.variants.length === 0) {
                const isSimple = this.isSimpleProduct();
                if (isSimple) {
                    this.formData.variants = [{
                        sku: this.variantGenerator.generateSku(),
                        option_values: [],
                        stock: 0,
                        enabled: true
                    }];
                }
            }
            this.renderVariants();
        } else if (step === 4) {
            this.priceCalculator.initializePrices();
            this.renderPricing();
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

            html += `
                <div class="option-card" data-option-id="${option.id}">
                    <div class="option-card-header">
                        <h6 class="option-card-title">${option.attribute_data.name}</h6>
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
                        ${option.values.map(val => `
                            <span class="option-value-badge">${val.attribute_data.name}</span>
                        `).join('')}
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

            html += `
                <div class="pricing-variant-section">
                    <div class="pricing-variant-header">${displayName} (${variant.sku})</div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Customer Group</th>
                                    <th>Price ($)</th>
                                    <th>Compare Price ($)</th>
                                    <th>Min Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            window.wizardData.customerGroups.forEach(group => {
                const price = variant.prices?.find(p => p.customer_group_id === group.id);
                const priceValue = price ? (price.price / 100).toFixed(2) : '0.00';
                const compareValue = price?.compare_price ? (price.compare_price / 100).toFixed(2) : '';
                const minQty = price?.min_quantity || 1;

                html += `
                    <tr>
                        <td>${group.attribute_data.name}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm" step="0.01" min="0"
                                   value="${priceValue}"
                                   onchange="wizard.updatePrice(${vIndex}, ${group.id}, 'price', this.value)">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" step="0.01" min="0"
                                   value="${compareValue}"
                                   onchange="wizard.updatePrice(${vIndex}, ${group.id}, 'compare_price', this.value)">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" min="1"
                                   value="${minQty}"
                                   onchange="wizard.updatePrice(${vIndex}, ${group.id}, 'min_quantity', this.value)">
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    /**
     * Update price
     */
    updatePrice(variantIndex, groupId, field, value) {
        const currentPrice = this.priceCalculator.getVariantPrice(variantIndex, groupId) || {
            customer_group_id: groupId,
            price: 0,
            compare_price: null,
            min_quantity: 1
        };

        if (field === 'price' || field === 'compare_price') {
            value = this.priceCalculator.parsePrice(value);
        } else if (field === 'min_quantity') {
            value = parseInt(value) || 1;
        }

        this.priceCalculator.updateVariantPrice(variantIndex, groupId, {
            ...currentPrice,
            [field]: value
        });
    }

    /**
     * Handle image upload
     */
    handleImageUpload(files) {
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

        validFiles.forEach(file => {
            this.formData.images.push(file);
        });

        this.renderImages();
    }

    /**
     * Render images
     */
    renderImages() {
        const grid = document.getElementById('imagePreviewGrid');

        if (this.formData.images.length === 0) {
            grid.style.display = 'none';
            return;
        }

        grid.style.display = 'flex';

        let html = '';

        this.formData.images.forEach((file, index) => {
            const url = URL.createObjectURL(file);

            html += `
                <div class="col-md-4 col-lg-3">
                    <div class="image-preview-card">
                        ${index === 0 ? '<span class="image-thumbnail-badge">Thumbnail</span>' : ''}
                        <img src="${url}" alt="${file.name}">
                        <div class="image-preview-overlay">
                            <div class="image-preview-actions">
                                <button type="button" class="btn btn-sm btn-danger" onclick="wizard.removeImage(${index})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        grid.innerHTML = html;
    }

    /**
     * Remove image
     */
    removeImage(index) {
        this.formData.images.splice(index, 1);
        this.renderImages();
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

        let html = `
            <div class="summary-item">
                <div class="summary-label">Product Name</div>
                <div class="summary-value">${this.formData.name.en || 'N/A'}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Product Type</div>
                <div class="summary-value">${productType?.attribute_data.name || 'N/A'}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Brand</div>
                <div class="summary-value">${brand?.attribute_data.name || 'N/A'}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Status</div>
                <div class="summary-value">${this.formData.status}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Options</div>
                <div class="summary-value">${this.formData.product_options.length} option(s)</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Variants</div>
                <div class="summary-value">${this.formData.variants.length} variant(s)</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Images</div>
                <div class="summary-value">${this.formData.images.length} image(s)</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Collections</div>
                <div class="summary-value">${this.formData.collections.length} collection(s)</div>
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
        formData.append('name[en]', this.formData.name.en);
        formData.append('name[fr]', this.formData.name.fr || '');
        formData.append('slug', this.formData.slug);
        formData.append('product_type_id', this.formData.product_type_id);
        if (this.formData.brand_id) {
            formData.append('brand_id', this.formData.brand_id);
        }
        formData.append('status', this.formData.status);
        formData.append('short_description[en]', this.formData.short_description.en || '');
        formData.append('short_description[fr]', this.formData.short_description.fr || '');
        formData.append('description[en]', this.formData.description.en || '');
        formData.append('description[fr]', this.formData.description.fr || '');

        // Product options
        this.formData.product_options.forEach((opt, i) => {
            Object.keys(opt).forEach(key => {
                formData.append(`product_options[${i}][${key}]`, opt[key]);
            });
        });

        // Variants
        this.formData.variants.forEach((variant, i) => {
            formData.append(`variants[${i}][sku]`, variant.sku);
            formData.append(`variants[${i}][stock]`, variant.stock);
            formData.append(`variants[${i}][enabled]`, variant.enabled ? 1 : 0);

            variant.option_values?.forEach((valId, j) => {
                formData.append(`variants[${i}][option_values][${j}]`, valId);
            });

            variant.prices?.forEach((price, j) => {
                Object.keys(price).forEach(key => {
                    formData.append(`variants[${i}][prices][${j}][${key}]`, price[key]);
                });
            });
        });

        // Images
        this.formData.images.forEach(file => {
            formData.append('images[]', file);
        });

        // Collections
        this.formData.collections.forEach(id => {
            formData.append('collections[]', id);
        });

        // Tags
        this.formData.tags.forEach(id => {
            formData.append('tags[]', id);
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
