<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cover' => $this->cover,
            'description' => $this->description,
            'price' => $this->price,
            'featured' => (bool) $this->featured,
            'online' => (bool) $this->online,
            'categories' => CategoryResource::collection($this->categories),
            'platforms' => PlatformResource::collection($this->platforms)
        ];
    }
}
