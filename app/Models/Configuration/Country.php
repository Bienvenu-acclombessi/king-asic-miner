<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'iso3',
        'iso2',
        'phonecode',
        'capital',
        'currency',
        'native',
        'emoji',
    ];

    /**
     * Get states
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
