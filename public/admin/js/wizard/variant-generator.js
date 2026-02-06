/**
 * VariantGenerator - Generates product variants from option combinations
 */
class VariantGenerator {
    constructor(wizard) {
        this.wizard = wizard;
    }

    /**
     * Generate all variant combinations from selected options
     */
    generateVariants() {
        const options = this.wizard.formData.product_options || [];

        if (options.length === 0) {
            // Create single default variant for simple products
            return [{
                sku: this.generateSku(),
                option_values: [],
                stock: 0,
                enabled: true
            }];
        }

        // Get options that affect variants (Variable products)
        const variantOptions = options.filter(opt => {
            const option = window.wizardData.productOptions.find(po => po.id === opt.option_id);
            return option && opt.affects_stock;
        });

        if (variantOptions.length === 0) {
            // No options affect stock, create single variant
            return [{
                sku: this.generateSku(),
                option_values: [],
                stock: 0,
                enabled: true
            }];
        }

        // Generate combinations
        const combinations = this.generateCombinations(variantOptions);

        // Create variants from combinations
        return combinations.map(combination => ({
            sku: this.generateSkuFromCombination(combination),
            option_values: combination.map(cv => cv.valueId),
            stock: 0,
            enabled: true,
            _combinationText: combination.map(cv => cv.valueName).join(' + ')
        }));
    }

    /**
     * Generate all combinations (cartesian product)
     */
    generateCombinations(variantOptions) {
        if (variantOptions.length === 0) {
            return [];
        }

        // Get all option values for each option
        const optionValueSets = variantOptions.map(opt => {
            const option = window.wizardData.productOptions.find(po => po.id === opt.option_id);
            return option.values.map(value => ({
                optionId: option.id,
                optionName: option.label || option.name || option.attribute_data?.name || 'Option',
                valueId: value.id,
                valueName: value.name || value.attribute_data?.name || 'Value #' + value.id
            }));
        });

        // Generate cartesian product
        return this.cartesianProduct(optionValueSets);
    }

    /**
     * Calculate cartesian product of arrays
     */
    cartesianProduct(arrays) {
        if (arrays.length === 0) return [[]];
        if (arrays.length === 1) return arrays[0].map(item => [item]);

        const [first, ...rest] = arrays;
        const restProduct = this.cartesianProduct(rest);

        return first.flatMap(item =>
            restProduct.map(prod => [item, ...prod])
        );
    }

    /**
     * Generate a random SKU
     */
    generateSku(prefix = '') {
        const random = Math.random().toString(36).substr(2, 6).toUpperCase();
        return prefix ? `${prefix}${random}` : `SKU-${random}`;
    }

    /**
     * Generate SKU from option combination
     */
    generateSkuFromCombination(combination) {
        // Extract initials from each value name
        const parts = combination.map(cv => {
            const words = cv.valueName.split(' ');
            if (words.length === 1) {
                return cv.valueName.substr(0, 3).toUpperCase();
            }
            return words.map(w => w[0]).join('').toUpperCase();
        });

        return parts.join('-');
    }

    /**
     * Apply SKU prefix to all variants
     */
    applySkuPrefix(prefix) {
        const variants = this.wizard.formData.variants || [];

        variants.forEach((variant, index) => {
            if (variant._combinationText) {
                variant.sku = this.generateSkuFromCombination(
                    variant.option_values.map(valueId => {
                        // Find the value name
                        for (const option of window.wizardData.productOptions) {
                            const value = option.values.find(v => v.id === valueId);
                            if (value) {
                                return {
                                    valueId: value.id,
                                    valueName: value.attribute_data.name
                                };
                            }
                        }
                        return { valueId, valueName: '' };
                    })
                );
                variant.sku = `${prefix}${variant.sku}`;
            } else {
                variant.sku = this.generateSku(prefix);
            }
        });

        this.wizard.formData.variants = variants;
    }

    /**
     * Apply stock to all variants
     */
    applyBulkStock(stock) {
        const variants = this.wizard.formData.variants || [];
        variants.forEach(variant => {
            variant.stock = parseInt(stock) || 0;
        });
        this.wizard.formData.variants = variants;
    }

    /**
     * Enable all variants
     */
    enableAllVariants() {
        const variants = this.wizard.formData.variants || [];
        variants.forEach(variant => {
            variant.enabled = true;
        });
        this.wizard.formData.variants = variants;
    }

    /**
     * Disable all variants
     */
    disableAllVariants() {
        const variants = this.wizard.formData.variants || [];
        variants.forEach(variant => {
            variant.enabled = false;
        });
        this.wizard.formData.variants = variants;
    }

    /**
     * Add a single variant manually
     */
    addVariantManually() {
        const variants = this.wizard.formData.variants || [];
        variants.push({
            sku: this.generateSku(),
            option_values: [],
            stock: 0,
            enabled: true,
            _manual: true
        });
        this.wizard.formData.variants = variants;
    }

    /**
     * Remove a variant
     */
    removeVariant(index) {
        const variants = this.wizard.formData.variants || [];
        variants.splice(index, 1);
        this.wizard.formData.variants = variants;
    }

    /**
     * Update variant data
     */
    updateVariant(index, data) {
        const variants = this.wizard.formData.variants || [];
        if (variants[index]) {
            variants[index] = { ...variants[index], ...data };
            this.wizard.formData.variants = variants;
        }
    }

    /**
     * Get variant display name
     */
    getVariantDisplayName(variant) {
        if (variant._combinationText) {
            return variant._combinationText;
        }

        if (variant.option_values && variant.option_values.length > 0) {
            const valueNames = variant.option_values.map(valueId => {
                for (const option of window.wizardData.productOptions) {
                    const value = option.values.find(v => v.id === valueId);
                    if (value) {
                        return value.name || value.attribute_data?.name || 'Value';
                    }
                }
                return '';
            }).filter(name => name);

            return valueNames.join(' + ') || 'Default Variant';
        }

        return 'Default Variant';
    }
}
