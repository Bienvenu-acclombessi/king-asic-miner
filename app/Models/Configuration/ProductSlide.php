<?php

namespace App\Models\Configuration;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSlide extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'background_image',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    /**
     * Get the product associated with this slide.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get only active slides.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get slides ordered by position.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
