<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Soulcodex\Keyable\Models\ApiKey;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tokens = $this->apiKeys->count();
        $token = $this->apiKeys->first();

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'birthday' => $this->birthday,
            'verification_token' => $this->verification_token,
            'verified_at' => $this->verified_at,
            'token' => $tokens ? $this->formatUserToken($token) : null,
            'orders' => OrderResource::collection($this->orders)
        ];
    }

    /**
     * Format user token
     *
     * @param ApiKey $token
     * @return void
     */
    public function formatUserToken(ApiKey $token)
    {
        return new TokenResource($token);
    }
}
