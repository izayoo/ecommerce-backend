<?php

namespace App\Http\Resources\Export;

use App\Enum\Constants;
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
        $status = Constants::STATUS_ARRAY;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'subtitle' => $this->subtitle,
            'draw_mechanics' => $this->draw_mechanics,
            'description'=> $this->description,
            'product'=> $this->product->name,
            'max_tickets'=> $this->max_tickets,
            'campaign_category'=> $this->campaignCategory->name,
            'start_date'=> $this->start_date->format('Y-m-d'),
            'end_date'=> $this->end_date->format('Y-m-d'),
            'draw_date'=> $this->draw_date->format('Y-m-d'),
            'media'=> $this->media->path .'/'. $this->media->filename,
            'banner' => $this->banner->path .'/'. $this->banner->filename,
            'is_featured'=> $this->is_featured ? 'true' : 'false',
            'status'=> $status[$this->status],
            'created_at'=> $this->created_at->format('Y-m-d')
        ];
    }
}
