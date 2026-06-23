<?php

namespace App\Exports;

use App\Models\Site;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SitesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Site::with('equipments.equipmentType')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Site',
            'Wilayah',
            'Status Site',
            'Teknisi Eksisting',
            'Non-Teknisi Eksisting',
            'Jenis Alat',
            'Status Alat',
            'Jumlah Alat',
        ];
    }

    public function map($site): array
    {
        $rows = [];
        $siteStatus = $site->status ? 'Aktif' : 'Non-Aktif';

        if ($site->equipments->isEmpty()) {
            $rows[] = [
                $site->name,
                $site->region,
                $siteStatus,
                $site->existing_technical_staff,
                $site->existing_non_technical_staff,
                '',
                '',
                '',
            ];
        } else {
            foreach ($site->equipments as $eq) {
                $eqStatus = $eq->status ? 'Aktif' : 'Non-Aktif';
                $eqType = $eq->equipmentType ? $eq->equipmentType->name : '';
                $rows[] = [
                    $site->name,
                    $site->region,
                    $siteStatus,
                    $site->existing_technical_staff,
                    $site->existing_non_technical_staff,
                    $eqType,
                    $eqStatus,
                    $eq->quantity,
                ];
            }
        }

        return $rows;
    }
}
