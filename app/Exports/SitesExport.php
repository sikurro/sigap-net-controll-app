<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SitesExport implements WithMultipleSheets
{
    use Exportable;

    protected $isTemplate;

    public function __construct(bool $isTemplate = false)
    {
        $this->isTemplate = $isTemplate;
    }

    public function sheets(): array
    {
        return [
            new SitesDataSheet($this->isTemplate),
            new EquipmentMasterSheet(),
        ];
    }
}
