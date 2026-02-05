<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_option_id',
        'name',
        'position',
    ];

    protected $casts = [
        'name' => 'array',
        'position' => 'integer',
    ];

    /**
     * Get the product option
     */
    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class);
    }

    /**
     * Get product variants
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_option_value_product_variant'
        );
    }
}
