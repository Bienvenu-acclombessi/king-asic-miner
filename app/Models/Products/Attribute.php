<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_group_id',
        'position',
        'name',
        'handle',
        'attribute_type',
        'section',
        'type',
        'required',
        'default_value',
        'configuration',
        'system',
        'description',
    ];

    protected $casts = [
        'name' => 'array',
        'configuration' => 'array',
        'required' => 'boolean',
        'system' => 'boolean',
    ];

    /**
     * Get the attribute group
     */
    public function attributeGroup(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class);
    }
}
