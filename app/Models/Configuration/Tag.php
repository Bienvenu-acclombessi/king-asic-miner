<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'image',
    ];

    /**
     * Get all taggable models
     */
    public function taggables(string $type): MorphToMany
    {
        return $this->morphedByMany($type, 'taggable');
    }
}
