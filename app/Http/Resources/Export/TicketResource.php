<?php

namespace App\Http\Resources\Export;

use App\Enum\Constants;
use App\Http\Resources\OrderProductResource;
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
            "ticket_no" => $this->ticket_no,
            "owner" => $this->orderProduct->order->user->email,
            "fname" => $this->orderProduct->order->user->fname,
            "lname" => $this->orderProduct->order->user->lname,
            "campaign" => $this->orderProduct->campaign->name,
            "product" => $this->orderProduct->product->name,
            "status" => $ticketStatus[$this->status],
            "created_at" => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
