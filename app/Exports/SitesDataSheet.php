<?php

namespace App\Exports;

use App\Models\Site;
use App\Models\EquipmentType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class SitesDataSheet implements FromCollection, WithTitle
{
    protected $isTemplate;

    public function __construct(bool $isTemplate = false)
    {
        $this->isTemplate = $isTemplate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $sites = Site::with('equipments.equipmentType')->get();
        $flatData = collect();

        if ($this->isTemplate || $sites->isEmpty()) {
            $equipmentTypes = EquipmentType::orderBy('name')->get();
            $flatData->push(['Nama Site', 'CONTOH TERMINAL PELABUHAN']);
            $flatData->push(['Wilayah', 'I']);
            $flatData->push(['Teknisi Eksisting', 10]);
            $flatData->push(['Non-Teknisi Eksisting', 5]);
            $flatData->push(['Jenis Alat', 'Jumlah Alat']);

            foreach ($equipmentTypes as $eq) {
                $flatData->push([$eq->name, 1]);
            }

            return $flatData;
        }

        foreach ($sites as $site) {
            $flatData->push(['Nama Site', $site->name]);
            $flatData->push(['Wilayah', $site->region]);
            $flatData->push(['Teknisi Eksisting', $site->existing_technical_staff]);
            $flatData->push(['Non-Teknisi Eksisting', $site->existing_non_technical_staff]);
            $flatData->push(['Jenis Alat', 'Jumlah Alat']);
            
            foreach ($site->equipments as $eq) {
                $eqType = $eq->equipmentType ? $eq->equipmentType->name : '';
                $flatData->push([$eqType, $eq->quantity]);
            }
            
            // Add an empty row for separation
            $flatData->push(['', '']);
        }

        return $flatData;
    }

    public function title(): string
    {
        return 'Data Site';
    }
}
