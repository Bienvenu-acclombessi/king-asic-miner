<?php

namespace App\Models\Discounts;

use App\Models\Configuration\Collection;
use App\Models\Customers\Customer;
use App\Models\Customers\CustomerGroup;
use App\Models\Orders\CartLine;
use App\Models\Products\Brand;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'handle',
        'coupon',
        'type',
        'discount_value',
        'is_active',
        'starts_at',
        'ends_at',
        'uses',
        'max_uses',
        'max_uses_per_user',
        'priority',
        'stop',
        'restriction',
        'data',
        'min_order_amount',
        'max_order_amount',
        'min_qty',
        'max_discount_amount',
        'apply_to_shipping',
        'exclude_sale_items',
        'individual_use',
        'free_shipping',
        'allowed_emails',
        'description',
    ];

    protected $casts = [
        'data' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'stop' => 'boolean',
        'is_active' => 'boolean',
        'apply_to_shipping' => 'boolean',
        'exclude_sale_items' => 'boolean',
        'individual_use' => 'boolean',
        'free_shipping' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
    ];

    /**
     * Get brands
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_discount')
            ->withPivot('type')
            ->withTimestamps();
    }

    /**
     * Get customer groups
     */
    public function customerGroups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class, 'customer_group_discount')
            ->withTimestamps();
    }

    /**
     * Get customers
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_discount')
            ->withTimestamps();
    }

    /**
     * Get collections
     */
    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'discount_collections')
            ->withPivot('type')
            ->withTimestamps();
    }

    /**
     * Get users
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'discount_user')
            ->withTimestamps();
    }

    /**
     * Get cart lines
     */
    public function cartLines(): BelongsToMany
    {
        return $this->belongsToMany(CartLine::class, 'cart_line_discount')
            ->withTimestamps();
    }

    /**
     * Check if coupon is currently valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->lt($now)) {
            return false;
        }

        if ($this->max_uses && $this->uses >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Check if coupon has reached max uses
     */
    public function hasReachedMaxUses(): bool
    {
        return $this->max_uses && $this->uses >= $this->max_uses;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->discount_value) / 100;

            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = $this->max_discount_amount;
            }

            return round($discount, 2);
        }

        if ($this->type === 'fixed') {
            return min($this->discount_value, $subtotal);
        }

        return 0;
    }

    /**
     * Validate against order conditions
     */
    public function validateOrderConditions(float $orderTotal, int $itemCount): array
    {
        $errors = [];

        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            $errors[] = "Minimum order amount of $" . number_format($this->min_order_amount, 2) . " required.";
        }

        if ($this->max_order_amount && $orderTotal > $this->max_order_amount) {
            $errors[] = "Maximum order amount of $" . number_format($this->max_order_amount, 2) . " exceeded.";
        }

        if ($this->min_qty && $itemCount < $this->min_qty) {
            $errors[] = "Minimum {$this->min_qty} items required in cart.";
        }

        return $errors;
    }
}
