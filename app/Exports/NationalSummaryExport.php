<?php

namespace App\Exports;

use App\Models\Site;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NationalSummaryExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $regionFilter;

    public function __construct(string $regionFilter = 'ALL')
    {
        $this->regionFilter = $regionFilter;
    }

    public function collection()
    {
        $query = Site::with('equipments');
        if ($this->regionFilter !== 'ALL' && !empty($this->regionFilter)) {
            $query->where('region', $this->regionFilter);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID Site',
            'Nama Terminal / Pelabuhan',
            'Wilayah',
            'Kelas Site',
            'Skema Kerja',
            'Total Unit Alat',
            'Teknisi Eksisting',
            'Rekomendasi Teknisi',
            'Selisih Teknisi',
            'Non-Teknis Eksisting',
            'Rekomendasi Non-Teknis',
            'Selisih Non-Teknis',
            'Status Kelayakan SDM'
        ];
    }

    public function map($site): array
    {
        $equipCount = $site->equipments->sum('quantity');
        $tNeed = floatval($site->technical_staff_needed);
        $tExist = intval($site->existing_technical_staff);
        $ntNeed = intval($site->non_technical_staff_needed);
        $ntExist = intval($site->existing_non_technical_staff);

        $tDiff = $tExist - $tNeed;
        $ntDiff = $ntExist - $ntNeed;

        $totalNeed = $tNeed + $ntNeed;
        $totalExist = $tExist + $ntExist;
        $status = 'Seimbang';
        if ($totalNeed > 0) {
            if ($totalExist < $totalNeed * 0.9) {
                $status = 'Under-staffed (Kekurangan Personel)';
            } elseif ($totalExist > $totalNeed * 1.1) {
                $status = 'Over-staffed (Kelebihan Personel)';
            }
        } elseif ($totalExist > 0) {
            $status = 'Over-staffed (Kelebihan Personel)';
        }

        return [
            $site->id,
            $site->name,
            $site->region,
            $site->site_class ?? '-',
            $site->work_scheme ?? 'Non-Shift',
            $equipCount,
            $tExist,
            round($tNeed, 2),
            round($tDiff, 2),
            $ntExist,
            $ntNeed,
            $ntDiff,
            $status
        ];
    }

    public function title(): string
    {
        return 'Rekapitulasi SDM';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
            ],
        ];
    }
}
