<?php

namespace App\Http\Controllers;

use App\Enum\Constants;
use App\Http\Requests\ContactUsRequest;
use App\Http\Resources\BannerTypeResource;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CarouselResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TicketResource;
use App\Mail\ContactUsEmail;
use App\Services\BannerService;
use App\Services\BannerTypeService;
use App\Services\CampaignCategoryService;
use App\Services\CampaignService;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class HomepageController extends Controller
{
    private CampaignService $campaignService;
    private CampaignCategoryService $campaignCategoryService;
    private ProductService $productService;
    private BannerTypeService $bannerTypeService;
    private BannerService $bannerService;
    private CartService $cartService;
    private TicketService $ticketService;

    public function __construct(
        CampaignService $campaignService,
        CampaignCategoryService $campaignCategoryService,
        ProductService $productService,
        BannerTypeService $bannerTypeService,
        BannerService $bannerService,
        CartService $cartService,
        TicketService $ticketService
    ) {
        $this->campaignService = $campaignService;
        $this->campaignCategoryService = $campaignCategoryService;
        $this->productService = $productService;
        $this->bannerTypeService = $bannerTypeService;
        $this->bannerService = $bannerService;
        $this->cartService = $cartService;
        $this->ticketService = $ticketService;
    }

    public function fetchFeaturedCampaigns()
    {
        return response()->json([
            'data' => CampaignResource::collection($this->campaignService->fetchFeatured())
        ], 200);
    }

    public function fetchCurrentCampaigns(Request $request)
    {
        $category = $request->has('category') ? $request->get('category') : null;

        return response()->json([
            'data' => CampaignResource::collection($this->campaignService->fetchCurrentCampaigns($category))
        ], 200);
    }

    public function campaignCategoryList(Request $request)
    {
        return response()->json([
            'data' => $this->campaignCategoryService->fetchActive($request->all())
        ], 200);
    }

    public function productList(Request $request)
    {
        return response()->json([
            'data' => ProductResource::collection($this->productService->fetchActive($request->all()))
        ], 200);
    }

    public function fetchCampaignWinners()
    {
        return response()->json([
            "data" => TicketResource::collection($this->ticketService->findWinners())
        ]);
    }

    public function contactUs(ContactUsRequest $request)
    {
        Mail::to(Constants::CONTACT_US_EMAIL)->send(new ContactUsEmail($request->all()));

        return response()->json([
            'message' => "Message sent."
        ]);
    }

    public function bannerTypeList(Request $request)
    {
        return response()->json([
            'data' => $this->bannerTypeService->fetchActive($request->all())
        ], 200);
    }

    public function fetchAllCarousel()
    {
        return response()->json([
            'data' => CarouselResource::make($this->bannerService->fetchAllCarousel())
        ], 200);

    }

    public function fetchAllSubBanners()
    {
        return response()->json([
            'data' => BannerTypeResource::collection($this->bannerService->fetchAllSubBanners())
        ], 200);
    }

    public function paymayaWebhook(Request $request)
    {
        $this->cartService->paymayaWebhook($request);
    }
}
