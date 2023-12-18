<?php

namespace App\Http\Resources;

use App\Enum\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'fname' => $this->fname,
            'lname' => $this->lname,
            'email' => $this->email,
            'country_code' => $this->country_code,
            'mobile_no' => $this->mobile_no,
            'birthdate' => $this->birthdate->format('Y-m-d'),
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'account_type' => $this->account_type,
            'last_login' => $this->last_login ? $this->last_login->format('Y-m-d') : null,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d'),
            'addresses' => UserAddressResource::collection($this->addresses),
            "orders" => UserOrdersResourceWithProducts::collection($this->orders),
        ];
    }
}
