<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'handle',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Get collections
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }
}
