<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo'
    ];

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'products_platforms',
            'platform_id',
            'product_id'
        );
    }
}
