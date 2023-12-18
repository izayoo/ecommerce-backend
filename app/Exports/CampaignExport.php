<?php

namespace App\Exports;

use App\Http\Resources\Export\CampaignResource;
use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CampaignExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function collection()
    {
        return CampaignResource::collection(Campaign::all());
    }

    public function headings(): array
    {
        return [
            'Id', 'Name','Subtitle', 'Draw Mechanics', 'Description', 'Product', 'Max Tickets',
            'Category', 'Start Date', 'End Date', 'Draw Date', 'Media Image', 'Banner Image', 'Is featured?',
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
