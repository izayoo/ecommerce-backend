<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResourceWithProducts;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    private $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function fetchAll(Request $request)
    {
        return response()->json([
            "data" => OrderResourceWithProducts::collection($this->service->fetchActive($request->all()))
        ]);
    }

    public function fetchAllPaginated(Request $request)
    {
        return response()->json([
            "data" => OrderResourceWithProducts::collection($this->service->fetchProductOrderPaginated($request->all()))->resource
        ]);
    }

    public function fetchOne(int $id)
    {
        return response()->json([
            "data" => OrderResourceWithProducts::make($this->service->find($id))
        ]);
    }

    public function export(Request $request)
    {
        $data = $request->all();
        if($data['paginate']){
            unset($data['paginate']);
        }

        return Excel::download(new OrderExport($data, $this->service), 'order_'.time().'_export.xlsx');
    }
}
