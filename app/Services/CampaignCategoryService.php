<?php

namespace App\Services;

use App\Models\CampaignCategory;
use App\Models\ProductCategory;

class CampaignCategoryService extends BaseService
{
    private CampaignCategory $model;

    public function __construct(CampaignCategory $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
