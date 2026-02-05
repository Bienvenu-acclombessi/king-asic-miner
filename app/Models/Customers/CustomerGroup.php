<?php

namespace App\Models\Customers;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomerGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'handle',
        'default',
        'attribute_data',
    ];

    protected $casts = [
        'default' => 'boolean',
        'attribute_data' => 'array',
    ];

    /**
     * Get customers
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_customer_group');
    }

    /**
     * Get products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'customer_group_product')
            ->withPivot(['purchasable', 'visible', 'enabled', 'starts_at', 'ends_at'])
            ->withTimestamps();
    }
}
