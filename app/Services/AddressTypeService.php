<?php

namespace App\Services;

use App\Models\AddressType;
use Illuminate\Database\Eloquent\Model;

class AddressTypeService extends BaseService
{
    private AddressType $model;

    public function __construct(AddressType $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
