<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAssociation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_parent_id',
        'product_target_id',
        'type',
    ];

    /**
     * Get the parent product
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_parent_id');
    }

    /**
     * Get the target product
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_target_id');
    }
}
