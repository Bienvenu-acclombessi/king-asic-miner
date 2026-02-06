/**
 * StepManager - Manages wizard navigation and step transitions
 */
class StepManager {
    constructor(wizard) {
        this.wizard = wizard;
        this.currentStep = 1;
        this.totalSteps = 6;
        this.completedSteps = new Set();
    }

    /**
     * Go to a specific step
     */
    goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > this.totalSteps) {
            return false;
        }

        // Hide current step
        document.querySelector(`.wizard-step[data-step="${this.currentStep}"]`).style.display = 'none';

        // Show target step
        document.querySelector(`.wizard-step[data-step="${stepNumber}"]`).style.display = 'block';

        // Update current step
        this.currentStep = stepNumber;

        // Update UI
        this.updateProgressBar();
        this.updateNavigationButtons();

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });

        return true;
    }

    /**
     * Go to next step
     */
    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.markStepCompleted(this.currentStep);
            return this.goToStep(this.currentStep + 1);
        }
        return false;
    }

    /**
     * Go to previous step
     */
    prevStep() {
        if (this.currentStep > 1) {
            return this.goToStep(this.currentStep - 1);
        }
        return false;
    }

    /**
     * Mark a step as completed
     */
    markStepCompleted(stepNumber) {
        this.completedSteps.add(stepNumber);
        this.updateProgressBar();
    }

    /**
     * Check if a step is completed
     */
    isStepCompleted(stepNumber) {
        return this.completedSteps.has(stepNumber);
    }

    /**
     * Update progress bar UI
     */
    updateProgressBar() {
        for (let i = 1; i <= this.totalSteps; i++) {
            const stepElement = document.querySelector(`.progress-step[data-step="${i}"]`);
            const circleElement = stepElement.querySelector('.step-circle');
            const lineElement = stepElement.nextElementSibling;

            // Reset classes
            stepElement.classList.remove('active', 'completed');
            circleElement.classList.remove('active', 'completed');
            if (lineElement && lineElement.classList.contains('progress-line')) {
                lineElement.classList.remove('completed');
            }

            // Set current state
            if (i === this.currentStep) {
                stepElement.classList.add('active');
                circleElement.classList.add('active');
            } else if (this.isStepCompleted(i)) {
                stepElement.classList.add('completed');
                circleElement.classList.add('completed');
                if (lineElement && lineElement.classList.contains('progress-line')) {
                    lineElement.classList.add('completed');
                }
            }
        }
    }

    /**
     * Update navigation buttons visibility
     */
    updateNavigationButtons() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const skipBtn = document.getElementById('skipBtn');
        const saveDraftBtn = document.getElementById('saveDraftBtn');
        const publishBtn = document.getElementById('publishBtn');

        // Hide all first
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
        skipBtn.style.display = 'none';
        saveDraftBtn.style.display = 'none';
        publishBtn.style.display = 'none';

        // Show based on current step
        if (this.currentStep > 1) {
            prevBtn.style.display = 'inline-block';
        }

        if (this.currentStep < this.totalSteps) {
            nextBtn.style.display = 'inline-block';

            // Show skip button for step 2 (options) if product is Simple
            if (this.currentStep === 2 && this.canSkipStep2()) {
                skipBtn.style.display = 'inline-block';
            }
        }

        if (this.currentStep === this.totalSteps) {
            saveDraftBtn.style.display = 'inline-block';
            publishBtn.style.display = 'inline-block';
        }
    }

    /**
     * Check if step 2 (options) can be skipped
     */
    canSkipStep2() {
        const productTypeSelect = document.getElementById('product_type_id');
        const selectedOption = productTypeSelect.options[productTypeSelect.selectedIndex];
        const productType = selectedOption?.dataset.type;

        return productType === 'simple';
    }

    /**
     * Validate current step
     */
    async validateCurrentStep() {
        switch (this.currentStep) {
            case 1:
                return this.validateStep1();
            case 2:
                return this.validateStep2();
            case 3:
                return this.validateStep3();
            case 4:
                return this.validateStep4();
            case 5:
                return this.validateStep5();
            case 6:
                return this.validateStep6();
            default:
                return true;
        }
    }

    /**
     * Validate Step 1: Basics
     */
    validateStep1() {
        const name = document.getElementById('name');
        const slug = document.getElementById('slug');
        const productTypeId = document.getElementById('product_type_id');

        let isValid = true;

        // Validate name
        if (!name.value.trim()) {
            this.showFieldError(name, 'Product name is required');
            isValid = false;
        } else {
            this.clearFieldError(name);
        }

        // Validate slug
        if (!slug.value.trim()) {
            this.showFieldError(slug, 'Slug is required');
            isValid = false;
        } else {
            this.clearFieldError(slug);
        }

        // Validate product type
        if (!productTypeId.value) {
            this.showFieldError(productTypeId, 'Product type is required');
            isValid = false;
        } else {
            this.clearFieldError(productTypeId);
        }

        return isValid;
    }

    /**
     * Validate Step 2: Options
     */
    validateStep2() {
        // If product is Simple, skip validation
        if (this.canSkipStep2()) {
            return true;
        }

        const selectedOptions = this.wizard.formData.product_options || [];

        // For Variable/Add-ons products, at least one option is recommended
        if (selectedOptions.length === 0) {
            // Show warning but allow to continue
            if (!confirm('You haven\'t added any options. Continue anyway?')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate Step 3: Variants
     */
    validateStep3() {
        const variants = this.wizard.formData.variants || [];

        if (variants.length === 0) {
            alert('Please add at least one variant');
            return false;
        }

        // Check if at least one variant is enabled
        const hasEnabledVariant = variants.some(v => v.enabled);
        if (!hasEnabledVariant) {
            alert('Please enable at least one variant');
            return false;
        }

        // Check if all variants have SKU
        const hasEmptySku = variants.some(v => !v.sku || !v.sku.trim());
        if (hasEmptySku) {
            alert('All variants must have a SKU');
            return false;
        }

        return true;
    }

    /**
     * Validate Step 4: Pricing
     */
    validateStep4() {
        const variants = this.wizard.formData.variants || [];

        // Check if all variants have a price
        for (const variant of variants) {
            if (!variant.price || variant.price <= 0) {
                alert(`Please enter a valid price for variant "${variant.sku}"`);
                return false;
            }

            // Check compare price if set
            if (variant.compare_price && variant.compare_price < variant.price) {
                alert(`Compare price must be higher than regular price for "${variant.sku}"`);
                return false;
            }
        }

        return true;
    }

    /**
     * Validate Step 5: Media
     */
    validateStep5() {
        // Images are optional, but show warning if no thumbnail
        const thumbnail = this.wizard.formData.thumbnail;

        if (!thumbnail) {
            if (!confirm('No main image uploaded. Continue anyway?')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate Step 6: Finalize
     */
    validateStep6() {
        // Optional validations
        const collections = document.getElementById('collections').selectedOptions;

        if (collections.length === 0) {
            if (!confirm('No collections selected. Continue anyway?')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Show field error
     */
    showFieldError(field, message) {
        field.classList.add('is-invalid');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }
    }

    /**
     * Clear field error
     */
    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }
}
