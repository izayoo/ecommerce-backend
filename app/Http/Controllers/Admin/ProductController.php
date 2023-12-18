<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CampaignExport;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignRequest;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    private $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function fetchAll(Request $request)
    {
        return response()->json([
            "data" => ProductResource::collection($this->service->fetchActive($request->all()))
        ]);
    }

    public function fetchAllPaginated(Request $request)
    {
        return response()->json([
            "data" => ProductResource::collection($this->service->fetchActivePaginated($request->all()))->resource
        ]);
    }

    public function fetchOne(int $id)
    {
        return response()->json([
            "data" => ProductResource::make($this->service->findActive($id))
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProductExport, 'products_'.time().'_export.xlsx');
    }

    public function create(ProductRequest $request)
    {
        return response()->json([
            'message' => 'Successfully created product',
            'data' => ProductResource::make($this->service->create($request->all()))
        ], 201);
    }

    public function update(int $id, ProductRequest $request)
    {
        return response()->json([
            'message' => 'Successfully created product',
            'data' => ProductResource::make($this->service->update($id, $request->all()))
        ], 202);
    }

    public function delete(int $id)
    {
        $this->service->softDelete($id);

        return response()->json([], 204);
    }
}
