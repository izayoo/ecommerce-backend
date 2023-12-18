<?php

namespace App\Http\Resources\Export;

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
        $accountTypes = Constants::ACCT_TYPE_ARRAY;
        $statuses = Constants::STATUS_ARRAY;

        return [
            'fname' => $this->fname,
            'lname' => $this->lname,
            'email' => $this->email,
            'mobile_no' => $this->country_code . $this->mobile_no,
            'birthdate' => $this->birthdate->format('Y-m-d'),
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'account_type' => $accountTypes[$this->account_type],
            'last_login' => $this->last_login ? $this->last_login->format('Y-m-d') : null,
            'status' => $statuses[$this->status],
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
