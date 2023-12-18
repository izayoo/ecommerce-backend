<?php

namespace App\Exports;

use App\Http\Resources\Export\UserResource;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function collection()
    {
        return UserResource::collection(User::all());
    }

    public function headings(): array
    {
        return [
            'First Name', 'Last Name', 'Email', 'Mobile Number', 'Birthdate', 'Nationality', 'Gender', 'Account Type',
            'Last Login', 'Status', 'Created Date'
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
