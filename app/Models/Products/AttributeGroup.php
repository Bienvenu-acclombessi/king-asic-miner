<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'attributable_type',
        'name',
        'handle',
        'position',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    /**
     * Get attributes
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}
