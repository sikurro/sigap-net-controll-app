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
            
            $existingTech = intval($row['teknisi_eksisting'] ?? 0);
            $existingNonTech = intval($row['non_teknisi_eksisting'] ?? 0);

            // Find or update the Site
            $site = Site::updateOrCreate(
                ['name' => $siteName, 'region' => $region],
                [
                    'status' => $siteStatus,
                    'existing_technical_staff' => $existingTech,
                    'existing_non_technical_staff' => $existingNonTech,
                ]
            );

            // Process equipment if available
            $eqTypeNameStr = strtolower(trim($row['jenis_alat'] ?? ''));

            if (!empty($eqTypeNameStr)) {
                $eqType = $equipmentTypes->get($eqTypeNameStr);

                if ($eqType) {
                    $eqStatusStr = strtolower(trim($row['status_alat'] ?? 'aktif'));
                    $eqStatus = $eqStatusStr === 'aktif';
                    $quantity = intval($row['jumlah_alat'] ?? 1);
                    if ($quantity < 1) $quantity = 1;

                    // Create or update equipment for this site
                    $site->equipments()->updateOrCreate(
                        [
                            'equipment_type_id' => $eqType->id,
                        ],
                        [
                            'status' => $eqStatus,
                            'quantity' => $quantity,
                        ]
                    );
                }
            }
        }
    }
}
