/**
 * PriceCalculator - Calculates prices with option modifiers
 */
class PriceCalculator {
    constructor(wizard) {
        this.wizard = wizard;
    }

    /**
     * Initialize prices for all variants
     */
    initializePrices() {
        const variants = this.wizard.formData.variants || [];
        const customerGroups = window.wizardData.customerGroups;

        variants.forEach(variant => {
            if (!variant.prices) {
                variant.prices = customerGroups.map(group => ({
                    customer_group_id: group.id,
                    price: 0,
                    compare_price: null,
                    min_quantity: 1
                }));
            }
        });

        this.wizard.formData.variants = variants;
    }

    /**
     * Calculate final price with option modifiers
     */
    calculateFinalPrice(basePrice, variant) {
        let finalPrice = parseInt(basePrice) || 0;

        // Get option values for this variant
        const optionValues = variant.option_values || [];

        // Apply modifiers from each option value
        optionValues.forEach(valueId => {
            const modifier = this.getOptionValueModifier(valueId);
            if (modifier) {
                finalPrice = this.applyModifier(finalPrice, modifier);
            }
        });

        return Math.max(0, finalPrice); // Ensure non-negative
    }

    /**
     * Get modifier for an option value
     */
    getOptionValueModifier(valueId) {
        for (const option of window.wizardData.productOptions) {
            const value = option.values.find(v => v.id === valueId);
            if (value && value.price_modifier) {
                return value.price_modifier;
            }
        }
        return null;
    }

    /**
     * Apply a price modifier
     */
    applyModifier(basePrice, modifier) {
        if (modifier.type === 'fixed') {
            return basePrice + parseInt(modifier.value || 0);
        } else if (modifier.type === 'percentage') {
            const percentage = parseFloat(modifier.value || 0);
            return Math.round(basePrice * (1 + percentage / 100));
        }
        return basePrice;
    }

    /**
     * Format price for display (cents to dollars)
     */
    formatPrice(cents) {
        const dollars = (cents / 100).toFixed(2);
        return `$${dollars}`;
    }

    /**
     * Parse price input (dollars to cents)
     */
    parsePrice(dollarString) {
        const cleaned = dollarString.replace(/[^0-9.]/g, '');
        const dollars = parseFloat(cleaned) || 0;
        return Math.round(dollars * 100);
    }

    /**
     * Update variant price
     */
    updateVariantPrice(variantIndex, customerGroupId, priceData) {
        const variants = this.wizard.formData.variants || [];
        const variant = variants[variantIndex];

        if (!variant) return;

        if (!variant.prices) {
            variant.prices = [];
        }

        const priceIndex = variant.prices.findIndex(
            p => p.customer_group_id === customerGroupId
        );

        if (priceIndex >= 0) {
            variant.prices[priceIndex] = {
                ...variant.prices[priceIndex],
                ...priceData
            };
        } else {
            variant.prices.push({
                customer_group_id: customerGroupId,
                ...priceData
            });
        }

        this.wizard.formData.variants = variants;
    }

    /**
     * Get price for a variant and customer group
     */
    getVariantPrice(variantIndex, customerGroupId) {
        const variants = this.wizard.formData.variants || [];
        const variant = variants[variantIndex];

        if (!variant || !variant.prices) {
            return null;
        }

        return variant.prices.find(p => p.customer_group_id === customerGroupId);
    }

    /**
     * Copy prices from one customer group to all
     */
    copyPricesToAllGroups(variantIndex, sourceGroupId) {
        const sourcePrice = this.getVariantPrice(variantIndex, sourceGroupId);

        if (!sourcePrice) return;

        const customerGroups = window.wizardData.customerGroups;

        customerGroups.forEach(group => {
            if (group.id !== sourceGroupId) {
                this.updateVariantPrice(variantIndex, group.id, {
                    price: sourcePrice.price,
                    compare_price: sourcePrice.compare_price,
                    min_quantity: sourcePrice.min_quantity
                });
            }
        });
    }

    /**
     * Validate all prices
     */
    validatePrices() {
        const variants = this.wizard.formData.variants || [];

        for (const variant of variants) {
            if (!variant.prices || variant.prices.length === 0) {
                return {
                    valid: false,
                    message: `Variant "${variant.sku}" has no prices`
                };
            }

            for (const price of variant.prices) {
                if (!price.price || price.price < 0) {
                    return {
                        valid: false,
                        message: `Invalid price for variant "${variant.sku}"`
                    };
                }

                if (price.compare_price && price.compare_price < price.price) {
                    return {
                        valid: false,
                        message: `Compare price must be higher than regular price for "${variant.sku}"`
                    };
                }

                if (price.min_quantity < 1) {
                    return {
                        valid: false,
                        message: `Minimum quantity must be at least 1 for "${variant.sku}"`
                    };
                }
            }
        }

        return { valid: true };
    }
}
