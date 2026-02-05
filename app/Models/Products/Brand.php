<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'attribute_data',
    ];

    protected $casts = [
        'attribute_data' => 'array',
    ];

    /**
     * Get products
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
