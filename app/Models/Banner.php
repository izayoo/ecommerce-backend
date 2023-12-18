<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Banner extends Model
{
    use HasFactory;

    protected $hidden = ['updated_at'];

    protected $fillable = ["banner_type_id", "media_id", "url_redirect", "status"];

    public function bannerType(): HasOne
    {
        return $this->hasOne(BannerType::class, 'id', 'banner_type_id');
    }

    public function media(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }
}
