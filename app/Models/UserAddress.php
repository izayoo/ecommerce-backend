<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    protected $hidden = ['updated_at'];

    protected $fillable = ['address_type_id', 'user_id', 'region', 'province', 'city', 'barangay', 'address1', 'address2'];

    public function addressType(): BelongsTo
    {
        return $this->belongsTo(AddressType::class);
    }
}
