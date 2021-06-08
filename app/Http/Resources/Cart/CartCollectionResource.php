<?php

namespace App\Http\Resources\Cart;

use App\Models\Cart;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollectionResource extends ResourceCollection
{
    public const SHIPPING_PRICE = 4;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'data' => CartResource::collection($this->collection),
            'cartTotal' => $this->collection->sum(function(Cart $cart){
                return $cart->quantity * $cart->product->price;
            })
        ];

        $data['tax'] = (float) number_format($data['cartTotal'] - $data['cartTotal'] / 1.21, 2);

        $data['subTotal'] = (float) number_format($data['cartTotal'] - $data['tax'], 2);

        $data['shipping'] = $data['cartTotal'] >= 1 ? self::SHIPPING_PRICE : 0;

        $data['grandTotal'] = $data['cartTotal'] > 0
            ? $data['cartTotal'] + self::SHIPPING_PRICE
            : 0;

        return $data;
    }
}
