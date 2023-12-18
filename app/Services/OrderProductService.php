<?php

namespace App\Services;

use App\Models\OrderProduct;

class OrderProductService extends BaseService
{
    private OrderProduct $model;

    public function __construct(OrderProduct $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
