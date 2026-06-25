<?php

namespace App\Exports;

use App\Models\EquipmentType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquipmentMasterSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        $equipments = EquipmentType::orderBy('code')->get();
        $flatData = collect();

        $no = 1;
        foreach ($equipments as $eq) {
            $flatData->push([
                'No' => $no++,
                'Kode Alat' => $eq->code,
                'Jenis Alat' => $eq->name,
                'Bobot' => $eq->weight,
            ]);
        }

        return $flatData;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Alat',
            'Jenis Alat (PENTING: Ejaan Harus Sama 100% Untuk Import)',
            'Bobot',
        ];
    }

    public function title(): string
    {
        return 'Master Jenis Alat';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
