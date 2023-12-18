<?php

namespace App\Exports;

use App\Http\Resources\Export\TicketResource;
use App\Services\TicketService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketExport implements FromCollection, WithHeadings, WithStyles
{
    private TicketService $service;
    private int $campaignId;

    public function __construct(int $campaignId, TicketService $service)
    {
        $this->service = $service;
        $this->campaignId = $campaignId;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function collection()
    {
        return TicketResource::collection($this->service->fetchActiveByCampaign($this->campaignId));
    }

    public function headings(): array
    {
        return [
            'Ticket No', 'Owner', 'First Name', 'Last Name', 'Campaign', 'Product',
            'Status', 'Created Date'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
