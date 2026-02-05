<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'purchasable_type',
        'purchasable_id',
        'type',
        'description',
        'option',
        'identifier',
        'unit_price',
        'unit_quantity',
        'quantity',
        'sub_total',
        'discount_total',
        'tax_breakdown',
        'tax_total',
        'total',
        'notes',
        'meta',
    ];

    protected $casts = [
        'unit_price' => 'integer',
        'unit_quantity' => 'integer',
        'quantity' => 'integer',
        'sub_total' => 'integer',
        'discount_total' => 'integer',
        'tax_total' => 'integer',
        'total' => 'integer',
        'meta' => 'array',
        'tax_breakdown' => 'array',
    ];

    /**
     * Get the order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the purchasable item
     */
    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }
}
