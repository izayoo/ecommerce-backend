<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $hidden = ['updated_at'];

    protected $fillable = [
        'name', 'description', 'slug', 'stock', 'price', 'product_category_id', 'media_id', 'status',
        'weight_in_grams', 'dimensions'
    ];

    public function productCategory() :HasOne
    {
        return $this->hasOne(ProductCategory::class, 'id', 'product_category_id');
    }

    public function media() :HasOne
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }
}
