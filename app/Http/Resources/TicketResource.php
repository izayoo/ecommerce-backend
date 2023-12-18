<?php

namespace App\Http\Resources;

use App\Enum\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ticketStatus = Constants::TICKET_STATUS_ARRAY;

        return [
            "id" => $this->id,
            "ticket_no" => $this->ticket_no,
            "order_product" => OrderProductResource::make($this->orderProduct),
            "status" => $ticketStatus[$this->status],
            "created_at" => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
