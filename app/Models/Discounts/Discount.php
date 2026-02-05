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
        'starts_at',
        'ends_at',
        'uses',
        'max_uses',
        'priority',
        'stop',
        'restriction',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'stop' => 'boolean',
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
}
