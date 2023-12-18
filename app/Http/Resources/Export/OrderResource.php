<?php

namespace App\Http\Resources\Export;

use App\Enum\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orderStatus = Constants::ORDER_STATUS_ARRAY;

        return [
            "order_no" => $this->order_no,
            "total" => $this->total,
            "user" => $this->user->fname . ' ' . $this->user->lname,
            "email" => $this->user->email,
            "payment_method" => $this->payment_method,
            "status" => $orderStatus[$this->status],
            "created_at" => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
