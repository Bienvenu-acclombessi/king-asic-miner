<?php

namespace App\Models\Products;

use App\Models\Customers\CustomerGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_group_id',
        'priceable_type',
        'priceable_id',
        'price',
        'compare_price',
        'tier',
        'min_quantity',
    ];

    protected $casts = [
        'price' => 'integer',
        'compare_price' => 'integer',
        'tier' => 'integer',
        'min_quantity' => 'integer',
    ];

    /**
     * Get the priceable model (product variant, etc.)
     */
    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the customer group
     */
    public function customerGroup(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class);
    }
}
