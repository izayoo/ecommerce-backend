<?php

namespace App\Http\Resources\Export;

use App\Enum\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'stock' => $this->stock,
            'price' => "₱".$this->price,
            'product_category' => $this->productCategory->name,
            'media' => $this->media->path .'/'. $this->media->filename,
            'status' => $status[$this->status],
            'created_at'=> $this->created_at->format('Y-m-d')
        ];
    }
}
