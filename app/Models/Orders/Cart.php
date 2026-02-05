<?php

namespace App\Models\Orders;

use App\Models\Customers\Customer;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'merged_id',
        'currency_code',
        'channel_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get cart lines
     */
    public function lines(): HasMany
    {
        return $this->hasMany(CartLine::class);
    }

    /**
     * Get shipping address
     */
    public function shippingAddress(): HasOne
    {
        return $this->hasOne(CartAddress::class)->where('type', 'shipping');
    }

    /**
     * Get billing address
     */
    public function billingAddress(): HasOne
    {
        return $this->hasOne(CartAddress::class)->where('type', 'billing');
    }

    /**
     * Get all addresses
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(CartAddress::class);
    }

    /**
     * Get the created order
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
