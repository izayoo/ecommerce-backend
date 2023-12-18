<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use Carbon\Carbon;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'stock' => $this->stock,
            'weight_in_grams' => $this->weight_in_grams,
            'dimensions' => $this->dimensions,
            'price' => number_format((float)$this->price, 2, '.', ''),
            'product_category' => $this->productCategory,
            'media' => $this->media,
            'status' => $this->status,
            'created_at' => $this->created_at
        ];
    }
}
