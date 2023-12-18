<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->product),
            'campaign' => new CampaignResource($this->campaign),
            'quantity' => $this->quantity,
            'is_for_donation' => $this->is_for_donation,
            'created_at' => $this->created_at
        ];
    }
}
