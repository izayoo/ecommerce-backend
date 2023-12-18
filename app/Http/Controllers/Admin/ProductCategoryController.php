<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductCategoryService;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    private ProductCategoryService $service;

    public function __construct(ProductCategoryService $service)
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
