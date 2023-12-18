<?php

namespace App\Exports;

use App\Models\Order;
use App\Services\OrderService;
use App\Http\Resources\Export\OrderResource;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;
    private OrderService $service;

    public function __construct($data, OrderService $service)
    {
        $this->data = $data;
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function collection()
    {
        $query = $this->data;
        $response = $this->service->fetchProductOrderPaginated($query);

        return OrderResource::collection($response);
    }

    public function headings(): array
    {
        return [
            "Order No", "Total", "User", "Email", "Payment_method", "Status", "Order Date"
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
