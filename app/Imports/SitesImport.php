<?php

namespace App\Imports;

use App\Models\Site;
use App\Models\EquipmentType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SitesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Cache equipment types to avoid N+1 queries during import
        $equipmentTypes = EquipmentType::all()->keyBy(function($item) {
            return strtolower(trim($item->name));
        });

        foreach ($rows as $row) {
            $siteName = trim($row['nama_site'] ?? '');
            $region = trim($row['wilayah'] ?? '');
            
            if (empty($siteName) || empty($region)) {
                continue; // Skip invalid rows without basic site info
            }

            $siteStatusStr = strtolower(trim($row['status_site'] ?? 'aktif'));
            $siteStatus = $siteStatusStr === 'aktif';

            // Find or create the Site
            $site = Site::firstOrCreate(
                ['name' => $siteName, 'region' => $region],
                ['status' => $siteStatus]
            );

            // Process equipment if available
            $eqName = trim($row['nama_alat'] ?? '');
            $eqCode = trim($row['kode_alat'] ?? '');
            $eqTypeNameStr = strtolower(trim($row['jenis_alat'] ?? ''));

            if (!empty($eqName) && !empty($eqCode) && !empty($eqTypeNameStr)) {
                $eqType = $equipmentTypes->get($eqTypeNameStr);

                if ($eqType) {
                    $eqStatusStr = strtolower(trim($row['status_alat'] ?? 'aktif'));
                    $eqStatus = $eqStatusStr === 'aktif';
                    $quantity = intval($row['jumlah_alat'] ?? 1);
                    if ($quantity < 1) $quantity = 1;

                    // Create or update equipment for this site
                    $site->equipments()->updateOrCreate(
                        [
                            'code' => $eqCode,
                        ],
                        [
                            'name' => $eqName,
                            'equipment_type_id' => $eqType->id,
                            'status' => $eqStatus,
                            'quantity' => $quantity,
                        ]
                    );
                }
            }
        }
    }
}
