<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarouselBannerRequest;
use App\Http\Requests\Admin\SubBannerRequest;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BannerTypeResource;
use App\Http\Resources\CarouselResource;
use App\Services\BannerService;
use App\Services\BannerTypeService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private BannerService $service;
    private BannerTypeService $bannerTypeService;

    public function __construct(
        BannerService $service,
        BannerTypeService $bannerTypeService
    ) {
        $this->service = $service;
        $this->bannerTypeService = $bannerTypeService;
    }

    public function listCarousel() {
        return response()->json([
            'data' => CarouselResource::make($this->service->fetchAllCarousel())
        ], 200);
    }

    public function listSubBanner() {
        return response()->json([
            'data' => BannerTypeResource::collection($this->service->fetchAllSubBanners())
        ], 200);
    }

    public function updateCarousel(CarouselBannerRequest $request)
    {
        return response()->json([
            'message' => 'Successfully updated carousel banners',
            'data' => CarouselResource::make($this->service->updateCarousel($request->all()))
        ], 201);
    }

    public function updateSubBanner(SubBannerRequest $request)
    {
        return response()->json([
            'message' => 'Successfully updated sub-banners',
            'data' => BannerTypeResource::collection($this->service->updateSubBanner($request->all()))
        ], 201);
    }


    public function fetchBannerTypeList(Request $request)
    {
        return response()->json([
            'data' => $this->bannerTypeService->fetchActive($request->all())
        ]);
    }
}
