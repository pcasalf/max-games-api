<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Product
 * @package App\Models
 * @property string name
 * @property float price
 * @property string description
 * @property bool featured
 * @property string cover
 * @property bool online
 */
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'description',
        'featured',
        'cover',
        'online'
    ];

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'products_categories',
            'product_id',
            'category_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(
            Platform::class,
            'products_platforms',
            'product_id',
            'platform_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function carts(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'product_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'order_products',
            'product_id',
            'order_id'
        );
    }
}
