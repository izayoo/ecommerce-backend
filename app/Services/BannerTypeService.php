<?php

namespace App\Services;

use App\Enum\Constants;
use App\Models\BannerType;

class BannerTypeService extends BaseService
{
    private BannerType $model;

    public function __construct(BannerType $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function fetchCarouselType()
    {
        return $this->model->where('id', Constants::CAROUSEL_BANNER)->first();
    }

    public function fetchSubBannerType()
    {
        return $this->model->whereIn('id', [
            Constants::LEFT_SUB_BANNER, Constants::RIGHT_SUB_BANNER
        ])->get();
    }
}
