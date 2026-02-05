<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'handle',
        'shared',
        'meta',
    ];

    protected $casts = [
        'name' => 'array',
        'label' => 'array',
        'shared' => 'boolean',
        'meta' => 'array',
    ];

    /**
     * Get products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_product_option')
            ->withPivot('position')
            ->orderByPivot('position');
    }

    /**
     * Get option values
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
