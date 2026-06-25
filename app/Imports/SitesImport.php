<?php

namespace App\Imports;

use App\Models\Site;
use App\Models\EquipmentType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SitesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Cache equipment types by name and by code
        $equipmentTypesByName = EquipmentType::all()->keyBy(function($item) {
            return strtolower(trim($item->name));
        });
        $equipmentTypesByCode = EquipmentType::all()->keyBy(function($item) {
            return strtolower(trim($item->code));
        });

        $parsedSites = [];
        $currentSite = null;

        foreach ($rows as $row) {
            $colA = trim($row[0] ?? '');
            $colA_lower = strtolower($colA);

            if (empty($colA)) {
                continue;
            }

            if ($colA_lower === 'nama site') {
                if ($currentSite !== null) {
                    $parsedSites[] = $currentSite;
                }

                $currentSite = [
                    'name' => trim($row[1] ?? ''),
                    'region' => '',
                    'existingTech' => 0,
                    'existingNonTech' => 0,
                    'equipments' => [],
                    'errors' => []
                ];
                continue;
            }

            if ($currentSite === null) {
                continue; // Wait until we find a site
            }

            if ($colA_lower === 'wilayah') {
                $currentSite['region'] = trim($row[1] ?? '');
                continue;
            }

            if ($colA_lower === 'teknisi eksisting') {
                $currentSite['existingTech'] = intval($row[1] ?? 0);
                continue;
            }

            if ($colA_lower === 'non-teknisi eksisting') {
                $currentSite['existingNonTech'] = intval($row[1] ?? 0);
                continue;
            }

            if ($colA_lower === 'jenis alat') {
                continue; // Header
            }

            // Otherwise, we treat ColA as equipment name/code
            $eqType = $equipmentTypesByName->get($colA_lower);
            if (!$eqType) {
                $eqType = $equipmentTypesByCode->get($colA_lower);
            }

            if ($eqType) {
                $currentSite['equipments'][] = [
                    'equipment_type_id' => $eqType->id,
                    'quantity' => max(1, intval($row[1] ?? 1)),
                ];
            } else {
                // Not found, record error
                $currentSite['errors'][] = $colA;
            }
        }

        if ($currentSite !== null) {
            $parsedSites[] = $currentSite;
        }

        $errorMessages = [];
        $successCount = 0;

        foreach ($parsedSites as $siteData) {
            if (empty($siteData['name'])) {
                continue;
            }

            if (count($siteData['errors']) > 0) {
                $errorMessages[] = "Site '{$siteData['name']}' ditolak karena alat tidak dikenal: " . implode(', ', $siteData['errors']);
                continue; // Skip saving this site
            }

            // Save valid site
            $site = Site::updateOrCreate(
                ['name' => $siteData['name'], 'region' => $siteData['region']],
                [
                    'existing_technical_staff' => $siteData['existingTech'],
                    'existing_non_technical_staff' => $siteData['existingNonTech'],
                ]
            );

            $importedEqIds = [];
            foreach ($siteData['equipments'] as $eqData) {
                $importedEqIds[] = $eqData['equipment_type_id'];
                $site->equipments()->updateOrCreate(
                    ['equipment_type_id' => $eqData['equipment_type_id']],
                    ['quantity' => $eqData['quantity']]
                );
            }

            // Remove equipments from database that were deleted in Excel
            $site->equipments()->whereNotIn('equipment_type_id', $importedEqIds)->delete();
            $successCount++;
        }

        if (count($errorMessages) > 0) {
            $summary = "Berhasil mengimpor $successCount site. Namun ada penolakan: <br>" . implode('<br>', $errorMessages);
            throw new \Exception($summary);
        }
    }
}
