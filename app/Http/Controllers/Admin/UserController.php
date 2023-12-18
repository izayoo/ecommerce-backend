<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function fetchAll(Request $request)
    {
        return response()->json([
            "data" => UserResource::collection($this->service->fetchActive($request->all()))
        ]);
    }

    public function fetchAllPaginated(Request $request)
    {
        return response()->json([
            "data" => UserResource::collection($this->service->fetchActivePaginated($request->all()))->resource
        ]);
    }

    public function fetchOne(int $id)
    {
        return response()->json([
            "data" => UserResource::make($this->service->findActive($id))
        ]);
    }

    public function delete(int $id)
    {
        $this->service->softDelete($id);

        return response()->json([], 204);
    }

    public function export(Request $request)
    {
        return Excel::download(new UserExport, 'users_'.time().'_export.xlsx');
    }
}
