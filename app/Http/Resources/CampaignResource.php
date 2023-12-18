<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use App\Services\CampaignService;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $id = $this->id;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'subtitle' => $this->subtitle,
            'draw_mechanics' => $this->draw_mechanics,
            'slug'=> $this->slug,
            'description'=> $this->description,
            'product'=> new ProductResource($this->product),
            'max_tickets' => $this->max_tickets,
            'tickets_issued' => Ticket::with('orderProduct', 'order')
                ->whereHas('orderProduct', function($query) use ($id) {
                    $query->where('campaign_id', $id);
                })->count(),
            'campaign_category'=> $this->campaignCategory,
            'start_date'=> $this->start_date->format('Y-m-d'),
            'end_date'=> $this->end_date->format('Y-m-d'),
            'draw_date'=> $this->draw_date->format('Y-m-d'),
            'media'=> $this->media,
            'banner' => $this->banner,
            'is_featured'=> $this->is_featured,
            'status'=> $this->status,
            'created_at'=> $this->created_at
        ];
   }
}
