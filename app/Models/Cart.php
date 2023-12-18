<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    use HasFactory;

    protected $hidden = ['updated_at'];

    protected $fillable = ['user_id', 'campaign_id', 'product_id', 'quantity', 'is_for_donation'];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function campaign(): HasOne
    {
        return $this->hasOne(Campaign::class, 'id', 'campaign_id');
    }
}
