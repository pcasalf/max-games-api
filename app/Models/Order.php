<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * @package App\Models
 * @property string status
 * @property float tax
 * @property float sub_total
 * @property float shipping
 * @property float total
 */
class Order extends Model
{
    use SoftDeletes;

    public const ORDER_IN_PROCESS = 'IN_PROCESS';
    public const ORDER_RECEIVED = 'RECEIVED';
    public const ORDER_PENDING_PAYMENT = 'PENDING_PAYMENT';
    public const ORDER_SHIPPED = 'SHIPPED';
    public const ORDER_COMPLETED = 'COMPLETED';

    protected $fillable =[
        'status',
        'tax',
        'sub_total',
        'shipping',
        'total'
    ];

    /**
     * @return float
     */
    public function getOrderTotalAttribute(): float
    {
        return $this->tax + $this->sub_total;
    }

    /**
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'order_products',
            'order_id',
            'product_id'
        );
    }
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
