<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname','lname','email','country_code','mobile_no','birthdate','nationality',
        'gender','account_type','last_login','password','status', 'verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at'
    ];

    protected $casts = [
        'birthdate' => 'date:Y-m-d',
        'last_login' => 'date:Y-m-d'
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id')
        ->where('status', '!=', -1);
    }

    public function billingAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id')
            ->where('address_type_id', 3);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id')
            ->where('address_type_id', 4);
    }
}
