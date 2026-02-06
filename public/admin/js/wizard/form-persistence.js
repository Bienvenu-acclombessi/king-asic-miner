/**
 * FormPersistence - Handles auto-save and restore from localStorage
 */
class FormPersistence {
    constructor(wizard) {
        this.wizard = wizard;
        this.storageKey = 'product_wizard_draft';
        this.autoSaveInterval = null;
    }

    /**
     * Start auto-save timer
     */
    startAutoSave() {
        // Auto-save every 30 seconds
        this.autoSaveInterval = setInterval(() => {
            this.save();
            this.showAutoSaveNotification();
        }, 30000);
    }

    /**
     * Stop auto-save timer
     */
    stopAutoSave() {
        if (this.autoSaveInterval) {
            clearInterval(this.autoSaveInterval);
            this.autoSaveInterval = null;
        }
    }

    /**
     * Save form data to localStorage
     */
    save() {
        try {
            const data = {
                formData: this.wizard.formData,
                currentStep: this.wizard.stepManager.currentStep,
                completedSteps: Array.from(this.wizard.stepManager.completedSteps),
                timestamp: Date.now()
            };

            localStorage.setItem(this.storageKey, JSON.stringify(data));
            return true;
        } catch (error) {
            console.error('Error saving to localStorage:', error);
            return false;
        }
    }

    /**
     * Load form data from localStorage
     */
    load() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            if (!stored) {
                return null;
            }

            const data = JSON.parse(stored);

            // Check if data is not too old (7 days)
            const maxAge = 7 * 24 * 60 * 60 * 1000; // 7 days in milliseconds
            if (Date.now() - data.timestamp > maxAge) {
                this.clear();
                return null;
            }

            return data;
        } catch (error) {
            console.error('Error loading from localStorage:', error);
            return null;
        }
    }

    /**
     * Clear saved data
     */
    clear() {
        try {
            localStorage.removeItem(this.storageKey);
            return true;
        } catch (error) {
            console.error('Error clearing localStorage:', error);
            return false;
        }
    }

    /**
     * Check if there's a saved draft
     */
    hasDraft() {
        return localStorage.getItem(this.storageKey) !== null;
    }

    /**
     * Prompt user to restore draft
     */
    promptRestore() {
        if (!this.hasDraft()) {
            return false;
        }

        const message = 'A draft was found. Would you like to restore it?';
        return confirm(message);
    }

    /**
     * Restore form data
     */
    restore() {
        const data = this.load();
        if (!data) {
            return false;
        }

        // Restore form data
        this.wizard.formData = data.formData;

        // Restore step state
        this.wizard.stepManager.currentStep = data.currentStep;
        this.wizard.stepManager.completedSteps = new Set(data.completedSteps);

        // Update UI
        this.wizard.stepManager.goToStep(data.currentStep);
        this.populateFormFields();

        return true;
    }

    /**
     * Populate form fields from restored data
     */
    populateFormFields() {
        const data = this.wizard.formData;

        // Step 1: Basics
        if (data.name) {
            document.getElementById('name').value = data.name;
        }
        if (data.slug) {
            document.getElementById('slug').value = data.slug;
        }
        if (data.product_type_id) {
            document.getElementById('product_type_id').value = data.product_type_id;
        }
        if (data.brand_id) {
            document.getElementById('brand_id').value = data.brand_id;
        }
        if (data.status) {
            document.getElementById('status').value = data.status;
        }
        if (data.short_description) {
            document.getElementById('short_description').value = data.short_description;
        }
        if (data.description) {
            document.getElementById('description').value = data.description;
        }

        // Step 6: Finalize
        if (data.collections) {
            const collectionsSelect = document.getElementById('collections');
            Array.from(collectionsSelect.options).forEach(option => {
                option.selected = data.collections.includes(parseInt(option.value));
            });
        }

        if (data.tags) {
            const tagsSelect = document.getElementById('tags');
            Array.from(tagsSelect.options).forEach(option => {
                option.selected = data.tags.includes(parseInt(option.value));
            });
        }

        if (data.customer_groups) {
            data.customer_groups.forEach(groupId => {
                const checkbox = document.getElementById(`cg_${groupId}`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        if (data.meta_title) {
            document.getElementById('meta_title').value = data.meta_title;
        }
        if (data.meta_description) {
            document.getElementById('meta_description').value = data.meta_description;
        }
        if (data.meta_keywords) {
            document.getElementById('meta_keywords').value = data.meta_keywords;
        }

        // Trigger re-render for options, variants, and pricing
        if (data.product_options) {
            this.wizard.renderOptions();
        }
        if (data.variants) {
            this.wizard.renderVariants();
            this.wizard.renderPricing();
        }
    }

    /**
     * Show auto-save notification
     */
    showAutoSaveNotification() {
        const toast = document.getElementById('autoSaveToast');
        if (toast) {
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 2000
            });
            bsToast.show();
        }
    }

    /**
     * Clean old drafts (older than 7 days)
     */
    cleanOldDrafts() {
        const keys = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && key.startsWith('product_wizard_draft')) {
                keys.push(key);
            }
        }

        keys.forEach(key => {
            try {
                const data = JSON.parse(localStorage.getItem(key));
                const maxAge = 7 * 24 * 60 * 60 * 1000;
                if (data && Date.now() - data.timestamp > maxAge) {
                    localStorage.removeItem(key);
                }
            } catch (error) {
                // Invalid data, remove it
                localStorage.removeItem(key);
            }
        });
    }
}
