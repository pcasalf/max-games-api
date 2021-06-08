<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Cart
 * @package App\Models
 * @property int quantity
 */
class Cart extends Model
{
    protected $fillable = [
        'product_id',
        'quantity'
    ];

    /**
     * @return float|int
     */
    public function getTotalItemAttribute()
    {
        return $this->quantity * $this->product->price;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
