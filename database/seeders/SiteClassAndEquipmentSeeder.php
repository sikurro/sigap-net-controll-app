<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteClass;
use App\Models\EquipmentType;

class SiteClassAndEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Site Classes
        $classes = [
            ['name' => 'Utama', 'min_weight' => 2501, 'max_weight' => null],
            ['name' => 'A', 'min_weight' => 1401, 'max_weight' => 2500],
            ['name' => 'B', 'min_weight' => 751, 'max_weight' => 1400],
            ['name' => 'C', 'min_weight' => 501, 'max_weight' => 750],
            ['name' => 'D', 'min_weight' => 251, 'max_weight' => 500],
            ['name' => 'E', 'min_weight' => 0, 'max_weight' => 250],
        ];

        foreach ($classes as $classData) {
            SiteClass::updateOrCreate(
                ['name' => $classData['name']],
                [
                    'min_weight' => $classData['min_weight'],
                    'max_weight' => $classData['max_weight'],
                ]
            );
        }

        // 2. Seed Equipment Types with weights and categories
        $equipments = [
            // Crane
            ['code' => 'GSU', 'name' => 'GRAB SHIP UNLOADER', 'weight' => 83, 'category' => 'Crane'],
            ['code' => 'CC', 'name' => 'CONTAINER CRANE', 'weight' => 77, 'category' => 'Crane'],
            ['code' => 'HMC', 'name' => 'HARBOUR MOBILE CRANE', 'weight' => 49, 'category' => 'Crane'],
            ['code' => 'HPC', 'name' => 'HARBOUR PORTAL CRANE', 'weight' => 49, 'category' => 'Crane'],
            ['code' => 'LC', 'name' => 'LUFFING CRANE', 'weight' => 45, 'category' => 'Crane'],
            ['code' => 'FC', 'name' => 'FLOATING CRANE', 'weight' => 45, 'category' => 'Crane'],
            ['code' => 'GJC', 'name' => 'GANTRY JIB CRANE', 'weight' => 45, 'category' => 'Crane'],
            ['code' => 'MCR', 'name' => 'MOBILE CRANE', 'weight' => 24, 'category' => 'Crane'],
            ['code' => 'FCR', 'name' => 'FIXED CRANE', 'weight' => 38, 'category' => 'Crane'],
            ['code' => 'ARTG', 'name' => 'AUTOMATE RUBBER TYRED GANTRY', 'weight' => 65, 'category' => 'Crane'],
            ['code' => 'ASC', 'name' => 'AUTOMATED STACKING CRANE', 'weight' => 65, 'category' => 'Crane'],
            ['code' => 'RTG', 'name' => 'RUBBER TYRED GANTRY', 'weight' => 41, 'category' => 'Crane'],
            ['code' => 'RMGC', 'name' => 'RAIL MOUNTED GANTRY CRANE', 'weight' => 65, 'category' => 'Crane'],
            ['code' => 'SC', 'name' => 'STRADDLE CARRIER', 'weight' => 35, 'category' => 'Crane'],

            // Mobile Equipment
            ['code' => 'RS', 'name' => 'REACH STACKER', 'weight' => 23, 'category' => 'Mobile Equipment'],
            ['code' => 'SLD', 'name' => 'SIDE LOADER', 'weight' => 18, 'category' => 'Mobile Equipment'],
            ['code' => 'FL 25T', 'name' => 'FORKLIFT 25 T', 'weight' => 11, 'category' => 'Mobile Equipment'],
            ['code' => 'FL 16T', 'name' => 'FORKLIFT 16 T', 'weight' => 10, 'category' => 'Mobile Equipment'],
            ['code' => 'FL 15T', 'name' => 'FORKLIFT 15 T', 'weight' => 9, 'category' => 'Mobile Equipment'],
            ['code' => 'FL 10T', 'name' => 'FORKLIFT 10 T', 'weight' => 8, 'category' => 'Mobile Equipment'],
            ['code' => 'FL 7T', 'name' => 'FORKLIFT 7 T', 'weight' => 7, 'category' => 'Mobile Equipment'],
            ['code' => 'FL 5T', 'name' => 'FORKLIFT 5 T', 'weight' => 6, 'category' => 'Mobile Equipment'],
            ['code' => 'FL 3T', 'name' => 'FORKLIFT 3 T', 'weight' => 5, 'category' => 'Mobile Equipment'],
            ['code' => 'FL 2.5T', 'name' => 'FORKLIFT 2.5 T', 'weight' => 5, 'category' => 'Mobile Equipment'],
            ['code' => 'TT', 'name' => 'TERMINAL TRACTOR', 'weight' => 14, 'category' => 'Mobile Equipment'],
            ['code' => 'CTT', 'name' => 'COMBINED TERMINAL TRACTOR', 'weight' => 12, 'category' => 'Mobile Equipment'],
            ['code' => 'HT', 'name' => 'HEAD TRUCK', 'weight' => 9, 'category' => 'Mobile Equipment'],
            ['code' => 'EXCA', 'name' => 'EXCAVATOR', 'weight' => 15, 'category' => 'Mobile Equipment'],
            ['code' => 'WLD', 'name' => 'WHEEL LOADER', 'weight' => 18, 'category' => 'Mobile Equipment'],

            // Others
            ['code' => 'CVY', 'name' => 'CONVEYOR', 'weight' => 53, 'category' => 'Others'],
            ['code' => 'TRN', 'name' => 'TRONTON', 'weight' => 11, 'category' => 'Others'],
            ['code' => 'BLD', 'name' => 'BULLDOZER', 'weight' => 11, 'category' => 'Others'],
            ['code' => 'DT', 'name' => 'DUMP TRUCK', 'weight' => 11, 'category' => 'Others'],
            ['code' => 'TLFR', 'name' => 'TRANSLIFTER', 'weight' => 3, 'category' => 'Others'],
            ['code' => 'LB', 'name' => 'LOWBED', 'weight' => 3, 'category' => 'Others'],
            ['code' => 'CHS', 'name' => 'CHASSIS', 'weight' => 2, 'category' => 'Others'],
            ['code' => 'JT', 'name' => 'JEMBATAN TIMBANG', 'weight' => 1, 'category' => 'Others'],
            ['code' => 'HPR', 'name' => 'HOPPER', 'weight' => 3, 'category' => 'Others'],
            ['code' => 'GRB', 'name' => 'GRAB', 'weight' => 3, 'category' => 'Others'],
            ['code' => 'BCT', 'name' => 'BUCKET', 'weight' => 1, 'category' => 'Others'],
            ['code' => 'RDR', 'name' => 'RAMPDOOR', 'weight' => 1, 'category' => 'Others'],
            ['code' => 'PMK', 'name' => 'Mobil PMK/Tangga/sweeper', 'weight' => 1, 'category' => 'Others'],
            ['code' => 'GST 2000', 'name' => 'GENSET 2000', 'weight' => 1, 'category' => 'Others'],
            ['code' => 'GST 1500', 'name' => 'GENSET 1500', 'weight' => 1, 'category' => 'Others'],
            ['code' => 'GST 1000', 'name' => 'GENSET 1000', 'weight' => 1, 'category' => 'Others'],
            ['code' => 'PMP', 'name' => 'POMPA CPO', 'weight' => 1, 'category' => 'Others'],
        ];

        // Also add mapping for existing codes that don't match the new ones
        $aliasMap = [
            'F' => ['code' => 'FL 3T', 'name' => 'FORKLIFT', 'weight' => 5, 'category' => 'Mobile Equipment'],
            'FD' => ['code' => 'FL 25T', 'name' => 'FORKLIFT DIESEL', 'weight' => 11, 'category' => 'Mobile Equipment'],
            'H' => ['code' => 'HT', 'name' => 'HEAD TRUCK', 'weight' => 9, 'category' => 'Mobile Equipment'],
            'W' => ['code' => 'WLD', 'name' => 'WHEEL LOADER', 'weight' => 18, 'category' => 'Mobile Equipment'],
            'S' => ['code' => 'SLD', 'name' => 'SIDE LOADER', 'weight' => 18, 'category' => 'Mobile Equipment'],
            'H1' => ['code' => 'HPR', 'name' => 'HOPPER', 'weight' => 3, 'category' => 'Others'],
            'GRAB' => ['code' => 'GRB', 'name' => 'GRAB', 'weight' => 3, 'category' => 'Others'],
            'EXC' => ['code' => 'EXCA', 'name' => 'EXCAVATOR', 'weight' => 15, 'category' => 'Mobile Equipment'],
            'G' => ['code' => 'GST 1000', 'name' => 'GENSET', 'weight' => 1, 'category' => 'Others'],
            'D' => ['code' => 'PMK', 'name' => 'Mobil PMK/Tangga/sweeper', 'weight' => 1, 'category' => 'Others'],
            'P' => ['code' => 'PMP', 'name' => 'POMPA CPO', 'weight' => 1, 'category' => 'Others'],
            'FC' => ['code' => 'FCR', 'name' => 'FIXED CRANE', 'weight' => 38, 'category' => 'Crane'],
            'JTB' => ['code' => 'JT', 'name' => 'JEMBATAN TIMBANG', 'weight' => 1, 'category' => 'Others'],
            'LF' => ['code' => 'LC', 'name' => 'LUFFING CRANE', 'weight' => 45, 'category' => 'Crane'],
            'B' => ['code' => 'BCT', 'name' => 'BUCKET', 'weight' => 1, 'category' => 'Others'],
            'EH' => ['code' => 'RMGC', 'name' => 'RAIL MOUNTED GANTRY CRANE', 'weight' => 65, 'category' => 'Crane'],
        ];

        foreach ($equipments as $eq) {
            EquipmentType::updateOrCreate(
                ['code' => $eq['code']],
                [
                    'name' => $eq['name'],
                    'weight' => $eq['weight'],
                    'category' => $eq['category'],
                ]
            );
        }

        // Apply aliases to handle database entries that were previously loaded with custom/short codes
        foreach ($aliasMap as $oldCode => $eq) {
            $existing = EquipmentType::where('code', $oldCode)->first();
            if ($existing) {
                $existing->update([
                    'weight' => $eq['weight'],
                    'category' => $eq['category'],
                ]);
            }
        }
    }
}
