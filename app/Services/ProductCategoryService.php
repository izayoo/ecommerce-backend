<?php

namespace App\Services;

use App\Models\ProductCategory;

class ProductCategoryService extends BaseService
{
    private ProductCategory $model;

    public function __construct(ProductCategory $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
