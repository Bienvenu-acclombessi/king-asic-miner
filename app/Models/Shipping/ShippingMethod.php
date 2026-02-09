<?php

namespace App\Models\Shipping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'handle',
        'description',
        'price_type',
        'price',
        'min_order_amount',
        'max_order_amount',
        'max_weight',
        'estimated_days_min',
        'estimated_days_max',
        'is_active',
        'display_order',
        'meta',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_order_amount' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'estimated_days_min' => 'integer',
        'estimated_days_max' => 'integer',
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Check if shipping method is available for given cart
     */
    public function isAvailableForCart($cartTotal, $cartWeight = null): bool
    {
        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check minimum order amount
        if ($this->min_order_amount && $cartTotal < $this->min_order_amount) {
            return false;
        }

        // Check maximum order amount
        if ($this->max_order_amount && $cartTotal > $this->max_order_amount) {
            return false;
        }

        // Check maximum weight
        if ($this->max_weight && $cartWeight && $cartWeight > $this->max_weight) {
            return false;
        }

        return true;
    }

    /**
     * Calculate shipping cost for given cart total
     */
    public function calculateCost($cartTotal): float
    {
        if ($this->price_type === 'free') {
            return 0;
        }

        if ($this->price_type === 'percentage') {
            return ($cartTotal * $this->price) / 100;
        }

        // Fixed price
        return (float) $this->price;
    }

    /**
     * Get estimated delivery time as string
     */
    public function getEstimatedDeliveryAttribute(): ?string
    {
        if ($this->estimated_days_min && $this->estimated_days_max) {
            return "{$this->estimated_days_min}-{$this->estimated_days_max} jours";
        }

        if ($this->estimated_days_min) {
            return "{$this->estimated_days_min}+ jours";
        }

        if ($this->estimated_days_max) {
            return "Jusqu'à {$this->estimated_days_max} jours";
        }

        return null;
    }

    /**
     * Scope: Only active shipping methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
}
