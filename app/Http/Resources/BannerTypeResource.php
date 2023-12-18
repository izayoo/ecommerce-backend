<?php

namespace App\Http\Resources;

use App\Enum\Constants;
use App\Models\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerTypeResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'banner' => BannerResource::make($this->banner ?? new Banner(['banner_type_id' => $this->getBannerTypeId()]))
        ];
    }

    private function getBannerTypeId()
    {
        if ($this->slug == Constants::RIGHT_SUB_BANNER_SLUG) {
            return Constants::RIGHT_SUB_BANNER;
        } elseif ($this->slug == Constants::LEFT_SUB_BANNER_SLUG) {
            return Constants::LEFT_SUB_BANNER;
        }

        return Constants::CAROUSEL_BANNER;
    }
}
