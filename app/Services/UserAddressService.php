<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAddress;

class UserAddressService extends BaseService
{
    private UserAddress $model;

    public function __construct(UserAddress $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function findByUserAndAddressType(int $user, int $addressType)
    {
        return $this->model->where('user_id', $user)->where('address_type_id', $addressType)->first();
    }

    public function fetchActiveByUser(int $user)
    {
        return $this->model->where('user_id', $user)->get();
    }
}
