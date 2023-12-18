<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Campaign extends Model
{
    use HasFactory;

    protected $hidden = ['updated_at'];

    protected $fillable = [
        'name', 'description', 'slug', 'product_id', 'max_tickets', 'campaign_category_id', 'media_id',
        'banner_id', 'is_featured', 'status', 'draw_date', 'start_date', 'end_date', 'is_for_donation',
        'subtitle', 'draw_mechanics'
    ];

    protected $casts = [
        'draw_date' => 'date:Y-m-d',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d'
    ];

    public function product() :HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function campaignCategory() :HasOne
    {
        return $this->hasOne(CampaignCategory::class, 'id', 'campaign_category_id');
    }

    public function media() :HasOne
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }

    public function banner() :HasOne
    {
        return $this->hasOne(Media::class, 'id', 'banner_id');
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'campaign_id', 'id');
    }
}
