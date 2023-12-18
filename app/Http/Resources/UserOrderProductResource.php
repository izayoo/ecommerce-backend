<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderProductResource extends JsonResource
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
            "product" => ProductResource::make($this->product),
            "campaign" => CampaignResource::make($this->campaign),
            "quantity" => $this->quantity,
            "is_for_donation" => $this->is_for_donation,
            "tickets" => $this->tickets
        ];
    }
}
