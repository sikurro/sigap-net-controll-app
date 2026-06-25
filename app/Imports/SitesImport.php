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
        // Cache equipment types to avoid N+1 queries during import
        $equipmentTypes = EquipmentType::all()->keyBy(function($item) {
            return strtolower(trim($item->name));
        });

        $currentSite = null;

        // Function to save the site block to the database
        $saveCurrentSite = function() use (&$currentSite) {
            if ($currentSite && !empty($currentSite['name'])) {
                $site = Site::updateOrCreate(
                    ['name' => $currentSite['name'], 'region' => $currentSite['region']],
                    [
                        'existing_technical_staff' => $currentSite['existingTech'],
                        'existing_non_technical_staff' => $currentSite['existingNonTech'],
                    ]
                );

                $importedEqIds = [];
                foreach ($currentSite['equipments'] as $eqData) {
                    $importedEqIds[] = $eqData['equipment_type_id'];
                    $site->equipments()->updateOrCreate(
                        ['equipment_type_id' => $eqData['equipment_type_id']],
                        ['quantity' => $eqData['quantity']]
                    );
                }

                // Sync: Remove equipments from database that were deleted in the Excel template
                $site->equipments()->whereNotIn('equipment_type_id', $importedEqIds)->delete();
            }
        };

        foreach ($rows as $row) {
            $colA = trim($row[0] ?? '');
            $colA_lower = strtolower($colA);

            if (empty($colA)) {
                continue; // Skip empty rows
            }

            if ($colA_lower === 'nama site') {
                // Save previous site if exists
                $saveCurrentSite();

                // Start new site block
                $currentSite = [
                    'name' => trim($row[1] ?? ''),
                    'region' => '',
                    'existingTech' => 0,
                    'existingNonTech' => 0,
                    'equipments' => []
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
                continue; // Skip the header row for equipment table
            }

            // Otherwise, we treat ColA as equipment name and ColB as quantity
            $eqType = $equipmentTypes->get($colA_lower);
            if ($eqType) {
                $currentSite['equipments'][] = [
                    'equipment_type_id' => $eqType->id,
                    'quantity' => max(1, intval($row[1] ?? 1)),
                ];
            }
        }

        // Save the last site
        $saveCurrentSite();
    }
}
