<?php

namespace App\Models\Orders;

use App\Models\Customers\Customer;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'cart_id',
        'channel_id',
        'status',
        'reference',
        'customer_reference',
        'sub_total',
        'discount_total',
        'discount_breakdown',
        'shipping_total',
        'shipping_breakdown',
        'tax_breakdown',
        'tax_total',
        'total',
        'notes',
        'currency_code',
        'compare_currency_code',
        'exchange_rate',
        'meta',
        'placed_at',
        'fingerprint',
        'new_customer',
    ];

    protected $casts = [
        'sub_total' => 'integer',
        'discount_total' => 'integer',
        'shipping_total' => 'integer',
        'tax_total' => 'integer',
        'total' => 'integer',
        'meta' => 'array',
        'tax_breakdown' => 'array',
        'discount_breakdown' => 'array',
        'shipping_breakdown' => 'array',
        'placed_at' => 'datetime',
        'new_customer' => 'boolean',
    ];

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get order lines
     */
    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    /**
     * Get order addresses
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(OrderAddress::class);
    }

    /**
     * Get transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
