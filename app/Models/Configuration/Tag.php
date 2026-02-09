<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'slug',
        'image',
        'meta',
    ];

    protected $casts = [
        'value' => 'array',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = static::generateUniqueSlug($tag->value);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('value')) {
                $tag->slug = static::generateUniqueSlug($tag->value, $tag->id);
            }
        });
    }

    protected static function generateUniqueSlug($value, $ignoreId = null)
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter++;
            $query = static::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    /**
     * Get all taggable models
     */
    public function taggables(string $type): MorphToMany
    {
        return $this->morphedByMany($type, 'taggable');
    }
}
