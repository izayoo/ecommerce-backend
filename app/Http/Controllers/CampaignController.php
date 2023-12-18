<?php

namespace App\Http\Controllers;

use App\Http\Resources\CampaignResource;
use App\Http\Resources\ProductResource;
use App\Services\CampaignService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    private CampaignService $campaignService;
    private ProductService $productService;

    public function __construct(
        CampaignService $campaignService,
        ProductService $productService
    ) {
        $this->campaignService = $campaignService;
        $this->productService = $productService;
    }

    public function findCampaignDetails(int $id)
    {
        return response()->json([
            "data" => new CampaignResource($this->campaignService->findActive($id))
        ]);
    }

    public function findCampaignDetailsBySlug(string $slug)
    {
        return response()->json([
            "data" => new CampaignResource($this->campaignService->findActiveBySlug($slug))
        ]);
    }

    public function findProductBySlug(string $slug)
    {
        return response()->json([
            "data" => new ProductResource($this->productService->findActiveBySlug($slug))
        ]);
    }

    public function fetchSuggestedCampaigns(int $id)
    {
        return response()->json([
            "data" => CampaignResource::collection($this->campaignService->findSuggested($id))
        ]);
    }

    public function findProduct(int $id)
    {
        return response()->json([
            "data" => new ProductResource($this->productService->findActiveBySlug($id))
        ]);
    }

    public function fetchProductCampaigns(int $id)
    {
        return response()->json([
            "data" => CampaignResource::collection($this->campaignService->fetchCampaignsByProduct($id))
        ]);
    }
}
