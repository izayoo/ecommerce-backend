<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CampaignExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignRequest;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CampaignController extends Controller
{
    private $service;

    public function __construct(CampaignService $service)
    {
        $this->service = $service;
    }

    public function fetchAll(Request $request)
    {
        return response()->json([
            "data" => CampaignResource::collection($this->service->fetchActive($request->all()))
        ]);
    }

    public function fetchAllPaginated(Request $request)
    {
        $search = $request->has('search') ? $request->get('search') : null;

        return response()->json([
            "data" => CampaignResource::collection($this->service->fetchActivePaginated($request->all()))->resource
        ]);
    }

    public function fetchOne(int $id)
    {
        return response()->json([
            "data" => CampaignResource::make($this->service->findActive($id))
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new CampaignExport, 'campaigns_'.time().'_export.xlsx');
    }

    public function create(CampaignRequest $request)
    {
        return response()->json([
            'message' => 'Successfully created product',
            'data' => CampaignResource::make($this->service->create($request->all()))
        ], 201);
    }

    public function update(int $id, CampaignRequest $request)
    {
        return response()->json([
            'message' => 'Successfully created product',
            'data' => CampaignResource::make($this->service->update($id, $request->all()))
        ], 202);
    }

    public function delete(int $id)
    {
        $this->service->softDelete($id);

        return response()->json([], 204);
    }
}
