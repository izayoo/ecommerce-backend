<?php

namespace App\Exports;

use App\Http\Resources\Export\ProductResource;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function collection()
    {
        return ProductResource::collection(Product::all());
    }

    public function headings(): array
    {
        return [
            'Id', 'Name','Description', 'Stock', 'Price', 'Category', 'Media Image',
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
