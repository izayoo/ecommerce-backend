<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BannerType extends Model
{
    use HasFactory;

    public function banner(): HasOne
    {
        return $this->hasOne(Banner::class, 'banner_type_id', 'id');
    }

    // Used for carousel
    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class);
    }
}
