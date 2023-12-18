<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CampaignCategoryService;
use Illuminate\Http\Request;

class CampaignCategoryController extends Controller
{

    private CampaignCategoryService $service;

    public function __construct(CampaignCategoryService $service)
    {
        $this->service = $service;
    }

    public function fetchAll(Request $request)
    {
        return response()->json([
            "data" => $this->service->fetchActive($request->all())
        ]);
    }
}
