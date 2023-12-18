<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TicketExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CampaignTicketController extends Controller
{
    private $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
    }

    public function fetchAll(Request $request, int $id)
    {
        return response()->json([
            "data" => TicketResource::collection($this->service->fetchActiveByCampaign($id))
        ]);
    }

    public function fetchAllPaginated(Request $request, int $id)
    {
        return response()->json([
            "data" => TicketResource::collection($this->service->fetchActivePaginatedByCampaign($id, $request->all()))->resource
        ]);
    }

    public function fetchOne(int $id, int $ticketId)
    {
        return response()->json([
            "data" => TicketResource::make($this->service->findActiveByCampaign($id, $ticketId))
        ]);
    }

    public function export(Request $request, int $id)
    {
        return Excel::download(new TicketExport($id, $this->service), 'tickets_'.time().'_export.xlsx');
    }

    public function setAsWinner(int $id)
    {
        return response()->json([
            "message" => "Successfully set ticket as winner",
            "data" => $this->service->setAsWinner($id)
        ]);
    }
}
