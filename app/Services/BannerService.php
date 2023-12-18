<?php

namespace App\Services;

use App\Enum\Constants;
use App\Models\Banner;

class BannerService extends BaseService
{
    private Banner $model;
    private BannerTypeService $bannerTypeService;

    public function __construct(
        Banner $model,
        BannerTypeService $bannerTypeService
    ) {
        parent::__construct($model);
        $this->model = $model;
        $this->bannerTypeService = $bannerTypeService;
    }

    public function fetchAllCarousel()
    {
        return $this->bannerTypeService->fetchCarouselType();
    }

    public function fetchAllSubBanners()
    {
        return $this->bannerTypeService->fetchSubBannerType();
    }

    public function updateCarousel(array $data)
    {
        $this->model->where('banner_type_id', Constants::CAROUSEL_BANNER)->delete();
        foreach ($data as $banner) {
            $banner['status'] = 1;
            $banner['banner_type_id'] = Constants::CAROUSEL_BANNER;
            $this->model->create($banner);
        }

        return $this->bannerTypeService->fetchCarouselType();
    }

    public function updateSubBanner(array $data)
    {
        $this->model->whereIn('banner_type_id', [Constants::LEFT_SUB_BANNER, Constants::RIGHT_SUB_BANNER])->delete();
        foreach ($data as $banner) {
            $banner['status'] = 1;
            $record = $this->model->where('banner_type_id', $banner['banner_type_id'])->first();
            if ($record) {
                $record->update($banner);
            } else {
                $this->model->create($banner);
            }
        }

        return $this->bannerTypeService->fetchSubBannerType();
    }
}
