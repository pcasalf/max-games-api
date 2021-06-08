<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'tax' => $this->tax,
            'subTotal' => $this->sub_total,
            'shipping' => $this->shipping,
            'total' => $this->total,
            'products' => ProductResource::collection($this->products)
        ];
    }
}
