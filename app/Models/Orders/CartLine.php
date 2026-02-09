<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CartLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'purchasable_type',
        'purchasable_id',
        'quantity',
        'meta',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Get the cart
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the purchasable item (product variant, etc.)
     */
    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get selected option values for this cart line
     */
    public function optionValues(): HasMany
    {
        return $this->hasMany(CartLineOptionValue::class);
    }
}
