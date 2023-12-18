<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignTicketsResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ticketService = new TicketService(new Ticket());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'subtitle' => $this->subtitle,
            'slug'=> $this->slug,
            'product'=> new ProductResource($this->product),
            'campaign_category'=> $this->campaignCategory,
            'start_date'=> $this->start_date->format('Y-m-d'),
            'media'=> $this->media,
            'banner' => $this->banner,
            'status'=> $this->status,
            'created_at'=> $this->created_at,
            'tickets' => TicketResource::collection($ticketService->getActiveTicketsByCampaignAndUser($this->id))
        ];
    }
}
