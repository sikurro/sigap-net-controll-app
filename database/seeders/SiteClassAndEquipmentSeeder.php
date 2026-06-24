<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteClass;
use App\Models\EquipmentType;
use App\Models\EquipmentCategoryBaseline;

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

        // 2. Seed Equipment Category Baselines
        $baselines = [
            ['category' => 'Crane', 'baseline' => 10],
            ['category' => 'Mobile Equipment', 'baseline' => 5],
            ['category' => 'Others', 'baseline' => 4],
        ];

        $categoryModels = [];
        foreach ($baselines as $base) {
            $categoryModels[strtolower($base['category'])] = EquipmentCategoryBaseline::updateOrCreate(
                ['category' => $base['category']],
                ['baseline' => $base['baseline']]
            );
        }

        // 3. Seed Equipment Types
        $equipments = array (
  0 => 
  array (
    'name' => 'GRAB SHIP UNLOADER',
    'code' => 'GSU',
    'weight' => 83,
  ),
  1 => 
  array (
    'name' => 'CONTAINER CRANE',
    'code' => 'CC',
    'weight' => 77,
  ),
  2 => 
  array (
    'name' => 'HARBOUR MOBILE CRANE',
    'code' => 'HMC',
    'weight' => 49,
  ),
  3 => 
  array (
    'name' => 'HARBOUR PORTAL CRANE',
    'code' => 'HPC',
    'weight' => 49,
  ),
  4 => 
  array (
    'name' => 'LUFFING CRANE',
    'code' => 'LC',
    'weight' => 45,
  ),
  5 => 
  array (
    'name' => 'FLOATING CRANE',
    'code' => 'FC',
    'weight' => 45,
  ),
  6 => 
  array (
    'name' => 'GANTRY JIB CRANE',
    'code' => 'GJC',
    'weight' => 45,
  ),
  7 => 
  array (
    'name' => 'MOBILE CRANE',
    'code' => 'MCR',
    'weight' => 24,
  ),
  8 => 
  array (
    'name' => 'FIXED CRANE',
    'code' => 'FCR',
    'weight' => 38,
  ),
  9 => 
  array (
    'name' => 'AUTOMATE RUBBER TYRED GANTRY',
    'code' => 'ARTG',
    'weight' => 65,
  ),
  10 => 
  array (
    'name' => 'AUTOMATED STACKING CRANE',
    'code' => 'ASC',
    'weight' => 65,
  ),
  11 => 
  array (
    'name' => 'RUBBER TYRED GANTRY',
    'code' => 'RTG',
    'weight' => 41,
  ),
  12 => 
  array (
    'name' => 'RAIL MOUNTED GANTRY CRANE',
    'code' => 'RMGC',
    'weight' => 65,
  ),
  13 => 
  array (
    'name' => 'STRADDLE CARRIER',
    'code' => 'SC',
    'weight' => 35,
  ),
  14 => 
  array (
    'name' => 'REACH STACKER',
    'code' => 'RS',
    'weight' => 23,
  ),
  15 => 
  array (
    'name' => 'SIDE LOADER',
    'code' => 'SLD',
    'weight' => 18,
  ),
  16 => 
  array (
    'name' => 'FORKLIFT 25 T',
    'code' => 'FL 25T',
    'weight' => 11,
  ),
  17 => 
  array (
    'name' => 'FORKLIFT 16 T',
    'code' => 'FL 16T',
    'weight' => 10,
  ),
  18 => 
  array (
    'name' => 'FORKLIFT 15 T',
    'code' => 'FL 15T',
    'weight' => 9,
  ),
  19 => 
  array (
    'name' => 'FORKLIFT 10 T',
    'code' => 'FL 10T',
    'weight' => 8,
  ),
  20 => 
  array (
    'name' => 'FORKLIFT 7 T',
    'code' => 'FL 7T',
    'weight' => 7,
  ),
  21 => 
  array (
    'name' => 'FORKLIFT 5 T',
    'code' => 'FL 5T',
    'weight' => 6,
  ),
  22 => 
  array (
    'name' => 'FORKLIFT 3 T',
    'code' => 'FL 3T',
    'weight' => 5,
  ),
  23 => 
  array (
    'name' => 'FORKLIFT 2.5 T',
    'code' => 'FL 2.5T',
    'weight' => 5,
  ),
  24 => 
  array (
    'name' => 'CONVEYOR',
    'code' => 'CVY',
    'weight' => 53,
  ),
  25 => 
  array (
    'name' => 'TERMINAL TRACTOR',
    'code' => 'TT',
    'weight' => 14,
  ),
  26 => 
  array (
    'name' => 'COMBINED TERMINAL TRACTOR',
    'code' => 'CTT',
    'weight' => 12,
  ),
  27 => 
  array (
    'name' => 'HEAD TRUCK',
    'code' => 'HT',
    'weight' => 9,
  ),
  28 => 
  array (
    'name' => 'CHASSIS',
    'code' => 'CHS',
    'weight' => 2,
  ),
  29 => 
  array (
    'name' => 'TRONTON',
    'code' => 'TRN',
    'weight' => 11,
  ),
  30 => 
  array (
    'name' => 'EXCAVATOR',
    'code' => 'EXCA',
    'weight' => 15,
  ),
  31 => 
  array (
    'name' => 'BULLDOZER',
    'code' => 'BLD',
    'weight' => 11,
  ),
  32 => 
  array (
    'name' => 'WHEEL LOADER',
    'code' => 'WLD',
    'weight' => 18,
  ),
  33 => 
  array (
    'name' => 'DUMP TRUCK',
    'code' => 'DT',
    'weight' => 11,
  ),
  34 => 
  array (
    'name' => 'TRANSLIFTER',
    'code' => 'TLFR',
    'weight' => 3,
  ),
  35 => 
  array (
    'name' => 'LOWBED',
    'code' => 'LB',
    'weight' => 3,
  ),
  36 => 
  array (
    'name' => 'JEMBATAN TIMBANG',
    'code' => 'JT',
    'weight' => 1,
  ),
  37 => 
  array (
    'name' => 'HOPPER',
    'code' => 'HPR',
    'weight' => 3,
  ),
  38 => 
  array (
    'name' => 'GRAB',
    'code' => 'GRB',
    'weight' => 3,
  ),
  39 => 
  array (
    'name' => 'BUCKET',
    'code' => 'BCT',
    'weight' => 1,
  ),
  40 => 
  array (
    'name' => 'RAMPDOOR',
    'code' => 'RDR',
    'weight' => 1,
  ),
  41 => 
  array (
    'name' => 'Mobil PMK/Tangga/sweeper',
    'code' => 'PMK',
    'weight' => 1,
  ),
  42 => 
  array (
    'name' => 'GENSET 2000',
    'code' => 'GST 2000',
    'weight' => 1,
  ),
  43 => 
  array (
    'name' => 'GENSET 1500',
    'code' => 'GST 1500',
    'weight' => 1,
  ),
  44 => 
  array (
    'name' => 'GENSET 1000',
    'code' => 'GST 1000',
    'weight' => 1,
  ),
  45 => 
  array (
    'name' => 'POMPA CPO',
    'code' => 'PMP',
    'weight' => 1,
  ),
  46 => 
  array (
    'name' => 'KELAS SITE',
    'code' => 'Min',
    'weight' => 0,
  ),
  47 => 
  array (
    'name' => 'Utama',
    'code' => 2501,
    'weight' => 0,
  ),
  48 => 
  array (
    'name' => 'A',
    'code' => 1401,
    'weight' => 2500,
  ),
  49 => 
  array (
    'name' => 'B',
    'code' => 751,
    'weight' => 1400,
  ),
  50 => 
  array (
    'name' => 'C',
    'code' => 501,
    'weight' => 750,
  ),
  51 => 
  array (
    'name' => 'D',
    'code' => 251,
    'weight' => 250,
  ),
  52 => 
  array (
    'name' => 'E',
    'code' => 0,
    'weight' => 250,
  ),
);

        $getCategory = function($name) {
            $n = strtolower($name);
            if (strpos($n, 'crane') !== false || strpos($n, 'gantry') !== false || strpos($n, 'straddle') !== false || strpos($n, 'unloader') !== false) return 'Crane';
            if (strpos($n, 'forklift') !== false || strpos($n, 'truck') !== false || strpos($n, 'loader') !== false || strpos($n, 'tractor') !== false || strpos($n, 'excavator') !== false || strpos($n, 'stacker') !== false || strpos($n, 'chassis') !== false || strpos($n, 'tronton') !== false || strpos($n, 'bulldozer') !== false || strpos($n, 'mobil') !== false) return 'Mobile Equipment';
            return 'Others';
        };

        foreach ($equipments as $eq) {
            $catKey = strtolower($getCategory($eq['name']));
            $catId = $categoryModels[$catKey]->id ?? null;
            EquipmentType::updateOrCreate(
                ['code' => $eq['code']],
                [
                    'name' => $eq['name'],
                    'weight' => $eq['weight'],
                    'category_id' => $catId,
                ]
            );
        }

        // 4. Seed Job Plans
        $jobPlans = array (
  0 => 
  array (
    'equipment_name' => 'REACH STACKER',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 82.5,
    'frequency_per_year' => 365,
  ),
  1 => 
  array (
    'equipment_name' => 'REACH STACKER',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 270,
    'frequency_per_year' => 36,
  ),
  2 => 
  array (
    'equipment_name' => 'REACH STACKER',
    'activity_name' => 'Service 500',
    'duration_minutes' => 405,
    'frequency_per_year' => 18,
  ),
  3 => 
  array (
    'equipment_name' => 'REACH STACKER',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 765,
    'frequency_per_year' => 9,
  ),
  4 => 
  array (
    'equipment_name' => 'REACH STACKER',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 900,
    'frequency_per_year' => 9,
  ),
  5 => 
  array (
    'equipment_name' => 'REACH STACKER',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 240,
    'frequency_per_year' => 24,
  ),
  6 => 
  array (
    'equipment_name' => 'REACH STACKER',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  7 => 
  array (
    'equipment_name' => 'FORKLIFT DIESEL',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  8 => 
  array (
    'equipment_name' => 'FORKLIFT DIESEL',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 180,
    'frequency_per_year' => 36,
  ),
  9 => 
  array (
    'equipment_name' => 'FORKLIFT DIESEL',
    'activity_name' => 'Service 500',
    'duration_minutes' => 285,
    'frequency_per_year' => 18,
  ),
  10 => 
  array (
    'equipment_name' => 'FORKLIFT DIESEL',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 765,
    'frequency_per_year' => 9,
  ),
  11 => 
  array (
    'equipment_name' => 'FORKLIFT DIESEL',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 900,
    'frequency_per_year' => 9,
  ),
  12 => 
  array (
    'equipment_name' => 'FORKLIFT DIESEL',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 240,
    'frequency_per_year' => 24,
  ),
  13 => 
  array (
    'equipment_name' => 'FORKLIFT DIESEL',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  14 => 
  array (
    'equipment_name' => 'FORKLIFT',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 45,
    'frequency_per_year' => 365,
  ),
  15 => 
  array (
    'equipment_name' => 'FORKLIFT',
    'activity_name' => 'Service 250',
    'duration_minutes' => 45,
    'frequency_per_year' => 36,
  ),
  16 => 
  array (
    'equipment_name' => 'FORKLIFT',
    'activity_name' => 'Service 500',
    'duration_minutes' => 45,
    'frequency_per_year' => 18,
  ),
  17 => 
  array (
    'equipment_name' => 'FORKLIFT',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 45,
    'frequency_per_year' => 9,
  ),
  18 => 
  array (
    'equipment_name' => 'FORKLIFT',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 135,
    'frequency_per_year' => 9,
  ),
  19 => 
  array (
    'equipment_name' => 'FORKLIFT',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 240,
    'frequency_per_year' => 24,
  ),
  20 => 
  array (
    'equipment_name' => 'FORKLIFT',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  21 => 
  array (
    'equipment_name' => 'HEADTRUCK',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  22 => 
  array (
    'equipment_name' => 'HEADTRUCK',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 180,
    'frequency_per_year' => 36,
  ),
  23 => 
  array (
    'equipment_name' => 'HEADTRUCK',
    'activity_name' => 'Service 500',
    'duration_minutes' => 270,
    'frequency_per_year' => 18,
  ),
  24 => 
  array (
    'equipment_name' => 'HEADTRUCK',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 360,
    'frequency_per_year' => 9,
  ),
  25 => 
  array (
    'equipment_name' => 'HEADTRUCK',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 765,
    'frequency_per_year' => 9,
  ),
  26 => 
  array (
    'equipment_name' => 'HEADTRUCK',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 180,
    'frequency_per_year' => 180,
  ),
  27 => 
  array (
    'equipment_name' => 'HEADTRUCK',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  28 => 
  array (
    'equipment_name' => 'EXCAVATOR',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 60,
    'frequency_per_year' => 365,
  ),
  29 => 
  array (
    'equipment_name' => 'EXCAVATOR',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  30 => 
  array (
    'equipment_name' => 'EXCAVATOR',
    'activity_name' => 'Service 500',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  31 => 
  array (
    'equipment_name' => 'EXCAVATOR',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  32 => 
  array (
    'equipment_name' => 'EXCAVATOR',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 765,
    'frequency_per_year' => 3,
  ),
  33 => 
  array (
    'equipment_name' => 'EXCAVATOR',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  34 => 
  array (
    'equipment_name' => 'WHEELOADER',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 52.5,
    'frequency_per_year' => 365,
  ),
  35 => 
  array (
    'equipment_name' => 'WHEELOADER',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  36 => 
  array (
    'equipment_name' => 'WHEELOADER',
    'activity_name' => 'Service 500',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  37 => 
  array (
    'equipment_name' => 'WHEELOADER',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 405,
    'frequency_per_year' => 3,
  ),
  38 => 
  array (
    'equipment_name' => 'WHEELOADER',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 495,
    'frequency_per_year' => 3,
  ),
  39 => 
  array (
    'equipment_name' => 'WHEELOADER',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 240,
    'frequency_per_year' => 8,
  ),
  40 => 
  array (
    'equipment_name' => 'WHEELOADER',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  41 => 
  array (
    'equipment_name' => 'SIDELOADER',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 82.5,
    'frequency_per_year' => 365,
  ),
  42 => 
  array (
    'equipment_name' => 'SIDELOADER',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 270,
    'frequency_per_year' => 36,
  ),
  43 => 
  array (
    'equipment_name' => 'SIDELOADER',
    'activity_name' => 'Service 500',
    'duration_minutes' => 405,
    'frequency_per_year' => 18,
  ),
  44 => 
  array (
    'equipment_name' => 'SIDELOADER',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 765,
    'frequency_per_year' => 9,
  ),
  45 => 
  array (
    'equipment_name' => 'SIDELOADER',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 900,
    'frequency_per_year' => 9,
  ),
  46 => 
  array (
    'equipment_name' => 'SIDELOADER',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 240,
    'frequency_per_year' => 24,
  ),
  47 => 
  array (
    'equipment_name' => 'SIDELOADER',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  48 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'DAILY INSPECTION RTG',
    'duration_minutes' => 120,
    'frequency_per_year' => 365,
  ),
  49 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'INSPECTION 1 MONTH RTG',
    'duration_minutes' => 270,
    'frequency_per_year' => 12,
  ),
  50 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'INSPECTION 3 MONTH RTG',
    'duration_minutes' => 315,
    'frequency_per_year' => 4,
  ),
  51 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'INSPECTION 6 MONTH RTG',
    'duration_minutes' => 405,
    'frequency_per_year' => 2,
  ),
  52 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'SERVICE 250 JAM RTG ZPMC-TYPE 2506C-E15 TAG2',
    'duration_minutes' => 285,
    'frequency_per_year' => 24,
  ),
  53 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'SERVICE 500 JAM RTG ZPMC-TYPE 2506C-E15 TAG2',
    'duration_minutes' => 360,
    'frequency_per_year' => 18,
  ),
  54 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'SERVICE 1000 JAM RTG ZPMC-TYPE 2506C-E15 TAG2',
    'duration_minutes' => 922.5,
    'frequency_per_year' => 6,
  ),
  55 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 270,
    'frequency_per_year' => 12,
  ),
  56 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE WIREROPE HOIST RTG',
    'duration_minutes' => 1530,
    'frequency_per_year' => 2,
  ),
  57 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING WIREROPE HOIST RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 36,
  ),
  58 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING CABLE BASKET SPREADER RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 36,
  ),
  59 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING SLIDING PAD GEAR BOX FLIPPER DAN TWISTLOCK RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 36,
  ),
  60 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING MOTOR AC / DC RTG',
    'duration_minutes' => 180,
    'frequency_per_year' => 36,
  ),
  61 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING OPEN GEAR TROLLEY RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 36,
  ),
  62 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING SPREADER SYSTEM RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 18,
  ),
  63 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX HOIST OIL RTG',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  64 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX TROLLEY OIL RTG',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  65 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING SKEWING RTG',
    'duration_minutes' => 270,
    'frequency_per_year' => 12,
  ),
  66 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE HYDRAULIC STEERING GANTRY OIL RTG',
    'duration_minutes' => 247.5,
    'frequency_per_year' => 3,
  ),
  67 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX TRIM GEAR OIL RTG',
    'duration_minutes' => 1530,
    'frequency_per_year' => 3,
  ),
  68 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING ROPESHAVE HOIST RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 36,
  ),
  69 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHECK CONDITIONS HEAD BLOCK, TWISLOCK, PIN, SPLITH PEN, BUSHING, DRUM CABLE, STRUCTURE (DPT) RTG',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  70 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING DRUM HOIST AND COUPLING HOIST RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 18,
  ),
  71 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING CHAIN GANTRY RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 36,
  ),
  72 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'GREASING BEARING, BOGGIE AND EQUALIZER PIN RTG',
    'duration_minutes' => 135,
    'frequency_per_year' => 12,
  ),
  73 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX DIFFERENTIAL OIL ALL GANTRY RTG',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  74 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'SERVICE AIR CONDITIONER CABIN OPERATOR DAN ELECTRIC ROOM RTG',
    'duration_minutes' => 270,
    'frequency_per_year' => 12,
  ),
  75 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE TRUSTHER BRAKE OIL RTG',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  76 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'INSULATION MOTOR HOIST RTG',
    'duration_minutes' => 270,
    'frequency_per_year' => 3,
  ),
  77 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'INSULATION GENERATOR RTG',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  78 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'INSULATION MOTOR TROLLEY RTG',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  79 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'INSULATION MOTOR GANTRY RTG',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  80 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX SKEWING OIL RTG',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  81 => 
  array (
    'equipment_name' => 'RTG',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  82 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 90,
    'frequency_per_year' => 365,
  ),
  83 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'SERVICE 250 JAM GOTTWALD',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  84 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'SERVICE 500H GOTTWALD',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  85 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'SERVICE 1000H GOTTWALD',
    'duration_minutes' => 270,
    'frequency_per_year' => 6,
  ),
  86 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 270,
    'frequency_per_year' => 16,
  ),
  87 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'CHANGE WIREROPE GOTTWALD 4406',
    'duration_minutes' => 2400,
    'frequency_per_year' => 1,
  ),
  88 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'CHANGE OIL HYDRAULIC GOTTWALD',
    'duration_minutes' => 735,
    'frequency_per_year' => 1,
  ),
  89 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION 1 MONTH',
    'duration_minutes' => 135,
    'frequency_per_year' => 12,
  ),
  90 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION 1 MONTH & 2 MONTH',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  91 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH',
    'duration_minutes' => 480,
    'frequency_per_year' => 12,
  ),
  92 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH & 2 MONTH',
    'duration_minutes' => 630,
    'frequency_per_year' => 6,
  ),
  93 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH & 3 MONTH',
    'duration_minutes' => 690,
    'frequency_per_year' => 3,
  ),
  94 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH, 2 MONTH, 3 MONTH',
    'duration_minutes' => 735,
    'frequency_per_year' => 2,
  ),
  95 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'CHANGE OIL HOIST REDUCTION GEAR UNIT',
    'duration_minutes' => 540,
    'frequency_per_year' => 2,
  ),
  96 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'CHANGE OIL SLEWING REDUCTION GEAR UNIT',
    'duration_minutes' => 540,
    'frequency_per_year' => 2,
  ),
  97 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'CHANGE OIL AXLE DIFFERENTIAL AND WHEEL HUB GEARBOX',
    'duration_minutes' => 540,
    'frequency_per_year' => 2,
  ),
  98 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'CHANGE OIL REDUCTION GEAR SWIVEL',
    'duration_minutes' => 540,
    'frequency_per_year' => 2,
  ),
  99 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION DIESEL ENGINE 6000 JAM',
    'duration_minutes' => 270,
    'frequency_per_year' => 1,
  ),
  100 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR HOIST 6000 JAM',
    'duration_minutes' => 480,
    'frequency_per_year' => 1,
  ),
  101 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR SLEWING 6000 JAM',
    'duration_minutes' => 480,
    'frequency_per_year' => 1,
  ),
  102 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR PUMP 6000 JAM',
    'duration_minutes' => 480,
    'frequency_per_year' => 1,
  ),
  103 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR CABLE REEL SPREADER',
    'duration_minutes' => 480,
    'frequency_per_year' => 1,
  ),
  104 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR EXTRA POWER',
    'duration_minutes' => 480,
    'frequency_per_year' => 1,
  ),
  105 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION SPREADER',
    'duration_minutes' => 720,
    'frequency_per_year' => 1,
  ),
  106 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INSPECTION HOOK',
    'duration_minutes' => 480,
    'frequency_per_year' => 1,
  ),
  107 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'CHANGE OIL GEARBOX CABLE REEL SPREADER',
    'duration_minutes' => 540,
    'frequency_per_year' => 1,
  ),
  108 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'INPECTION TRAVO EXTRA POWER',
    'duration_minutes' => 720,
    'frequency_per_year' => 1,
  ),
  109 => 
  array (
    'equipment_name' => 'HMC',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  110 => 
  array (
    'equipment_name' => 'HOPPER',
    'activity_name' => 'GREASING HOPPER',
    'duration_minutes' => 60,
    'frequency_per_year' => 12,
  ),
  111 => 
  array (
    'equipment_name' => 'HOPPER',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 25,
    'frequency_per_year' => 365,
  ),
  112 => 
  array (
    'equipment_name' => 'HOPPER',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  113 => 
  array (
    'equipment_name' => 'GRAB',
    'activity_name' => 'GREASING GRAB',
    'duration_minutes' => 60,
    'frequency_per_year' => 12,
  ),
  114 => 
  array (
    'equipment_name' => 'GRAB',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 50,
    'frequency_per_year' => 365,
  ),
  115 => 
  array (
    'equipment_name' => 'GRAB',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  116 => 
  array (
    'equipment_name' => 'EXC',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 60,
    'frequency_per_year' => 365,
  ),
  117 => 
  array (
    'equipment_name' => 'EXC',
    'activity_name' => 'SERVICE 250 JAM',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  118 => 
  array (
    'equipment_name' => 'EXC',
    'activity_name' => 'SERVICE 500 JAM',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  119 => 
  array (
    'equipment_name' => 'EXC',
    'activity_name' => 'SERVICE 750 JAM',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  120 => 
  array (
    'equipment_name' => 'EXC',
    'activity_name' => 'SERVICE 1000 JAM',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  121 => 
  array (
    'equipment_name' => 'EXC',
    'activity_name' => 'SERVICE 2000 JAM',
    'duration_minutes' => 495,
    'frequency_per_year' => 3,
  ),
  122 => 
  array (
    'equipment_name' => 'EXC',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  123 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'DAILY INSPECTION CC',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  124 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE WIREROPE HOIST CC',
    'duration_minutes' => 1530,
    'frequency_per_year' => 1,
  ),
  125 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE SPREADER HYDRAULIC OIL CC',
    'duration_minutes' => 480,
    'frequency_per_year' => 6,
  ),
  126 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE WIREROPE BOOM CC',
    'duration_minutes' => 2160,
    'frequency_per_year' => 1,
  ),
  127 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING WIREROPE BOOM CC',
    'duration_minutes' => 180,
    'frequency_per_year' => 18,
  ),
  128 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING WIREROPE HOIST CC',
    'duration_minutes' => 180,
    'frequency_per_year' => 36,
  ),
  129 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING BEARING ROPESHAVE HOIST CC',
    'duration_minutes' => 270,
    'frequency_per_year' => 18,
  ),
  130 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING CABLE BASKET SPREADER CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 18,
  ),
  131 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING SLIDING PAD GEAR BOX FLIPPER DAN TWISTLOCK CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 18,
  ),
  132 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING BEARING ROPESHAVE BOOM CC',
    'duration_minutes' => 135,
    'frequency_per_year' => 12,
  ),
  133 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING WHEEL BEARING GANTRY CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  134 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING PIN BOOM CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  135 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE GEAR BOX OIL HOIST CC',
    'duration_minutes' => 480,
    'frequency_per_year' => 3,
  ),
  136 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE GEAR BOX OIL BOOM CC',
    'duration_minutes' => 480,
    'frequency_per_year' => 3,
  ),
  137 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING RAIL AND GEAR MAN LIFT CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 36,
  ),
  138 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING MOTOR DC CC',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  139 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'INSPECTION 3 MONTH CC',
    'duration_minutes' => 382.5,
    'frequency_per_year' => 4,
  ),
  140 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'INSPECTION 1 MONTH CC',
    'duration_minutes' => 315,
    'frequency_per_year' => 12,
  ),
  141 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'INSPECTION 6 MONTH CC',
    'duration_minutes' => 427.5,
    'frequency_per_year' => 2,
  ),
  142 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING DRUM AND COUPLING HOIST CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 18,
  ),
  143 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING DRUM AND COUPLING TROLLEY CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 18,
  ),
  144 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING ROPESHAVE TROLLEY CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 18,
  ),
  145 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'GREASING WHELL BEARING TROLLEY CC',
    'duration_minutes' => 90,
    'frequency_per_year' => 18,
  ),
  146 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE EMERGENCY BOOM HYDRAULIC OIL CC',
    'duration_minutes' => 480,
    'frequency_per_year' => 6,
  ),
  147 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'SERVICE AIR CONDITIONER CC',
    'duration_minutes' => 540,
    'frequency_per_year' => 12,
  ),
  148 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHECK CARBON BRUSH MOTOR HOIST CC',
    'duration_minutes' => 135,
    'frequency_per_year' => 36,
  ),
  149 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHECK CARBON BRUSH MOTOR TROLLEY CC',
    'duration_minutes' => 135,
    'frequency_per_year' => 36,
  ),
  150 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHECK CARBON BRUSH MOTOR GANTRY CC',
    'duration_minutes' => 135,
    'frequency_per_year' => 12,
  ),
  151 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHECK CARBON BRUSH MOTOR BOOM CC',
    'duration_minutes' => 135,
    'frequency_per_year' => 12,
  ),
  152 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'INSULATION MOTOR HOIST CC',
    'duration_minutes' => 270,
    'frequency_per_year' => 3,
  ),
  153 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'INSULATION MOTOR BOOM CC',
    'duration_minutes' => 270,
    'frequency_per_year' => 3,
  ),
  154 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'INSULATION MOTOR TROLLEY CC',
    'duration_minutes' => 270,
    'frequency_per_year' => 3,
  ),
  155 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'INSULATION MOTOR GANTRY CC',
    'duration_minutes' => 270,
    'frequency_per_year' => 3,
  ),
  156 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE TRUSTER BRAKE OIL CC',
    'duration_minutes' => 480,
    'frequency_per_year' => 3,
  ),
  157 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE GEAR BOX OIL GANTRY CC',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  158 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHANGE GEAR BOX OIL TROLLEY CC',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  159 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'CHECK CONDITION HEAD BLOCK, PIN, SPLITH PEN, BUSHING, DRUM CABLE AND STRUCTURE (DPT) UNIT CC',
    'duration_minutes' => 270,
    'frequency_per_year' => 6,
  ),
  160 => 
  array (
    'equipment_name' => 'CC',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  161 => 
  array (
    'equipment_name' => 'GENSET',
    'activity_name' => 'DAILY INSPECTION CUMMINS GENSET 1500 KVA',
    'duration_minutes' => 37.5,
    'frequency_per_year' => 365,
  ),
  162 => 
  array (
    'equipment_name' => 'GENSET',
    'activity_name' => 'SERVICE ENGINE 250 HOURS CUMMINS GENSET 1500 KVA',
    'duration_minutes' => 22.5,
    'frequency_per_year' => 8,
  ),
  163 => 
  array (
    'equipment_name' => 'GENSET',
    'activity_name' => 'SERVICE ENGINE 500 HOURS CUMMINS GENSET 1500 KVA',
    'duration_minutes' => 60,
    'frequency_per_year' => 8,
  ),
  164 => 
  array (
    'equipment_name' => 'GENSET',
    'activity_name' => 'SERVICE ENGINE 1000 HOURS CUMMINS GENSET 1500 KVA',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  165 => 
  array (
    'equipment_name' => 'GENSET',
    'activity_name' => 'SERVICE ENGINE 2000 HOURS CUMMINS GENSET 1500 KVA',
    'duration_minutes' => 135,
    'frequency_per_year' => 4,
  ),
  166 => 
  array (
    'equipment_name' => 'GENSET',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  167 => 
  array (
    'equipment_name' => 'DAMKAR',
    'activity_name' => 'DAILY INSPECTION DAMKAR DUTRO-W04 D-TR',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  168 => 
  array (
    'equipment_name' => 'DAMKAR',
    'activity_name' => 'SERVICE 1000 KM DAMKAR',
    'duration_minutes' => 360,
    'frequency_per_year' => 8,
  ),
  169 => 
  array (
    'equipment_name' => 'DAMKAR',
    'activity_name' => 'SERVICE 250 KM DAMKAR',
    'duration_minutes' => 180,
    'frequency_per_year' => 8,
  ),
  170 => 
  array (
    'equipment_name' => 'DAMKAR',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  171 => 
  array (
    'equipment_name' => 'DAMKAR',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  172 => 
  array (
    'equipment_name' => 'POMPA',
    'activity_name' => 'SERVICE ENGINE 250 JAM POMPA CPO DEUTZ 150T/J',
    'duration_minutes' => 45,
    'frequency_per_year' => 8,
  ),
  173 => 
  array (
    'equipment_name' => 'POMPA',
    'activity_name' => 'SERVICE ENGINE 500 JAM POMPA CPO DEUTZ 150T/J',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 8,
  ),
  174 => 
  array (
    'equipment_name' => 'POMPA',
    'activity_name' => 'SERVICE ENGINE 750 JAM POMPA CPO DEUTZ 150T/J',
    'duration_minutes' => 135,
    'frequency_per_year' => 4,
  ),
  175 => 
  array (
    'equipment_name' => 'POMPA',
    'activity_name' => 'SERVICE ENGINE 1000 JAM POMPA CPO DEUTZ 150T/J',
    'duration_minutes' => 135,
    'frequency_per_year' => 4,
  ),
  176 => 
  array (
    'equipment_name' => 'POMPA',
    'activity_name' => 'DAILY INSPECTION POMPA CPO DEUTZ 150T/J',
    'duration_minutes' => 30,
    'frequency_per_year' => 365,
  ),
  177 => 
  array (
    'equipment_name' => 'POMPA',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  178 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'DAILY INSPECTION TT TERBERG YT220 (AT)',
    'duration_minutes' => 60,
    'frequency_per_year' => 365,
  ),
  179 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'SERVICE 250 JAM TT TERBERG YT220 (AT)',
    'duration_minutes' => 180,
    'frequency_per_year' => 5,
  ),
  180 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'SERVICE 2000 JAM TT TERBERG YT220 (AT)',
    'duration_minutes' => 180,
    'frequency_per_year' => 2,
  ),
  181 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'SERVICE 6000 JAM TT TERBERG YT220 (AT)',
    'duration_minutes' => 360,
    'frequency_per_year' => 0,
  ),
  182 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'SERVICE 1000 JAM TT TERBERG YT220 (AT)',
    'duration_minutes' => 405,
    'frequency_per_year' => 2,
  ),
  183 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'SERVICE 500 JAM TT TERBERG YT220 (AT)',
    'duration_minutes' => 495,
    'frequency_per_year' => 5,
  ),
  184 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'SERVICE 750 JAM TT TERBERG YT220 (AT)',
    'duration_minutes' => 180,
    'frequency_per_year' => 5,
  ),
  185 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 240,
    'frequency_per_year' => 8,
  ),
  186 => 
  array (
    'equipment_name' => 'TT',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  187 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'DAILY INSPECTION SC DE52-DC13 074A',
    'duration_minutes' => 75,
    'frequency_per_year' => 365,
  ),
  188 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'SERVICE WEEKLY',
    'duration_minutes' => 135,
    'frequency_per_year' => 48,
  ),
  189 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'SERVICE 500 JAM SC DE52-DC13 074A',
    'duration_minutes' => 315,
    'frequency_per_year' => 4,
  ),
  190 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'SERVICE 1000 JAM SC DE52-DC13 074A',
    'duration_minutes' => 315,
    'frequency_per_year' => 4,
  ),
  191 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'SERVICE 2000 JAM SC DE52-DC13 074A',
    'duration_minutes' => 720,
    'frequency_per_year' => 4,
  ),
  192 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'SERVICE 4000 JAM SC DE52-DC13 074A',
    'duration_minutes' => 810,
    'frequency_per_year' => 3,
  ),
  193 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'SERVICE 6000 JAM SC DE52-DC13 074A',
    'duration_minutes' => 855,
    'frequency_per_year' => 3,
  ),
  194 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'SERVICE 8000 JAM SC DE52-DC13 074A',
    'duration_minutes' => 810,
    'frequency_per_year' => 3,
  ),
  195 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'SERVICE 12000 JAM SC DE52-DC13 074A',
    'duration_minutes' => 900,
    'frequency_per_year' => 3,
  ),
  196 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'CHANGE WIREROPE HOIST',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  197 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'GREASING SLIDING PAD SPREADER',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  198 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'GREASING PIN DAN BEARING',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  199 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'CHANGE TRUSTER BRAKE OIL',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  200 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'CHANGE GEAR BOX OIL GANTRY',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  201 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'CHANGE GEAR BOX OIL HOIST',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  202 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'CHECK CONDITION HEAD BLOCK, PIN, SPLITH PEN, BUSHING, DRUM CABLE AND STRUCTURE (DPT) UNIT',
    'duration_minutes' => 720,
    'frequency_per_year' => 3,
  ),
  203 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'INSULATION MOTOR HOIST',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  204 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'INSULATION MOTOR GANTRY',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  205 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'INSULATION MOTOR SPREADER',
    'duration_minutes' => 180,
    'frequency_per_year' => 3,
  ),
  206 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 360,
    'frequency_per_year' => 8,
  ),
  207 => 
  array (
    'equipment_name' => 'SC',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  208 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'DAILY INSPECTION FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 90,
    'frequency_per_year' => 365,
  ),
  209 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'INSPECTION 1 MONTH FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 195,
    'frequency_per_year' => 12,
  ),
  210 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'MEGGER MOTOR SLEWING FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  211 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'MEGGER MOTOR LUFFING FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  212 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'MEGGER MOTOR HOIST FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  213 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'GREASING WIREROPE HOIST & LUFFING FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  214 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'GREASING ALL BEARING FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  215 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'CHANGE GEARBOX HOIST OIL FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  216 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'CHANGE GEARBOX LUFFING OIL FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  217 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'CHANGE GEARBOX SLEWING OIL FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  218 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'CHANGE REDUCER OIL FIXED CRANE GYM80- 20 TON',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  219 => 
  array (
    'equipment_name' => 'FIXED CRANE',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  220 => 
  array (
    'equipment_name' => 'JTB',
    'activity_name' => 'DAILY INSPECTION JT 80T',
    'duration_minutes' => 45,
    'frequency_per_year' => 365,
  ),
  221 => 
  array (
    'equipment_name' => 'JTB',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  222 => 
  array (
    'equipment_name' => 'LF',
    'activity_name' => 'SERVICE 250 JAM LUFFING CRANE WIKA-LELANGON',
    'duration_minutes' => 180,
    'frequency_per_year' => 8,
  ),
  223 => 
  array (
    'equipment_name' => 'LF',
    'activity_name' => 'SERVICE 250 JAM LUFFING CRANE WIKA-LELANGON',
    'duration_minutes' => 180,
    'frequency_per_year' => 8,
  ),
  224 => 
  array (
    'equipment_name' => 'LF',
    'activity_name' => 'SERVICE 250 JAM LUFFING CRANE WIKA-LELANGON',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  225 => 
  array (
    'equipment_name' => 'LF',
    'activity_name' => 'SERVICE 1000 JAM LUFFING CRANE WIKA-LELANGON',
    'duration_minutes' => 315,
    'frequency_per_year' => 4,
  ),
  226 => 
  array (
    'equipment_name' => 'LF',
    'activity_name' => 'SERVICE 2000 JAM LUFFING CRANE WIKA-LELANGON',
    'duration_minutes' => 360,
    'frequency_per_year' => 3,
  ),
  227 => 
  array (
    'equipment_name' => 'LF',
    'activity_name' => 'DAILY INSPECTION LUFFING CRANE WIKA-LELANGON',
    'duration_minutes' => 52.5,
    'frequency_per_year' => 365,
  ),
  228 => 
  array (
    'equipment_name' => 'LF',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  229 => 
  array (
    'equipment_name' => 'BUCKET',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 52.5,
    'frequency_per_year' => 365,
  ),
  230 => 
  array (
    'equipment_name' => 'BUCKET',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  231 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'DAILY INSPECTION EH DCT 80 TAD 760 VE',
    'duration_minutes' => 82.5,
    'frequency_per_year' => 365,
  ),
  232 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE 250 JAM EH DCT 80 TAD 760 VE',
    'duration_minutes' => 270,
    'frequency_per_year' => 12,
  ),
  233 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE 500 JAM EH DCT 80 TAD 760 VE',
    'duration_minutes' => 405,
    'frequency_per_year' => 6,
  ),
  234 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE 1000 JAM  EH DCT 80 TAD 760 VE',
    'duration_minutes' => 765,
    'frequency_per_year' => 3,
  ),
  235 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE 2000 JAM  EH DCT 80 TAD 760 VE',
    'duration_minutes' => 900,
    'frequency_per_year' => 3,
  ),
  236 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 240,
    'frequency_per_year' => 8,
  ),
  237 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  238 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CONVEYOR',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  239 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'DAILY INSPECTION EH DCT 80 TAD 760 VE',
    'duration_minutes' => 82.5,
    'frequency_per_year' => 365,
  ),
  240 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING',
    'duration_minutes' => 1440,
    'frequency_per_year' => 24,
  ),
  241 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PELUMASAN',
    'duration_minutes' => 1440,
    'frequency_per_year' => 24,
  ),
  242 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN MANLIFT & GREASING REL MANLIFT',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  243 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN SERVICE HOIST',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  244 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CLEANING PANEL AREA',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  245 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CLEANING PANEL OUTDOOR',
    'duration_minutes' => 360,
    'frequency_per_year' => 12,
  ),
  246 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK DUMPER CHUTE',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  247 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK BUFFER HOPPER',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  248 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK SENSOR',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  249 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK AND CLEANING SAFETY DEVICE',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  250 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CLEANING & CHECK DRIVE PANEL',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  251 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CLEANING CUBICLE PANEL',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  252 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK CABLE TRAY & TERMINATION',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  253 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CLEANING PC CONTROL ROOM',
    'duration_minutes' => 180,
    'frequency_per_year' => 2,
  ),
  254 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE GEARBOX OIL BC01',
    'duration_minutes' => 720,
    'frequency_per_year' => 1,
  ),
  255 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE GEARBOX OIL BC02',
    'duration_minutes' => 720,
    'frequency_per_year' => 1,
  ),
  256 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE GEARBOX OIL BC03',
    'duration_minutes' => 720,
    'frequency_per_year' => 1,
  ),
  257 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE GEARBOX OIL TC',
    'duration_minutes' => 360,
    'frequency_per_year' => 1,
  ),
  258 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK COUNTER WEIGHT',
    'duration_minutes' => 360,
    'frequency_per_year' => 1,
  ),
  259 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'INSULATION MOTOR',
    'duration_minutes' => 720,
    'frequency_per_year' => 1,
  ),
  260 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  261 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'INSTALASI DAN ALAT BANTU',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  262 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PELAYANAN BONGKAR MUAT KAPAL BERLIAN MIRAH',
    'duration_minutes' => 90,
    'frequency_per_year' => 365,
  ),
  263 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENYEDIAAN TANGGA TKBM MIRAH',
    'duration_minutes' => 90,
    'frequency_per_year' => 365,
  ),
  264 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN / PENGGANTIAN WIRE ROPE SPREADER MIRAH',
    'duration_minutes' => 90,
    'frequency_per_year' => 365,
  ),
  265 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SETTING ALATA HMC UNTUK KAPAL BARU DAN PERGESERAN ALAT HMC',
    'duration_minutes' => 90,
    'frequency_per_year' => 365,
  ),
  266 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN GARDU EXSISTING 20 KV',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  267 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN KIOS Q 1',
    'duration_minutes' => 105,
    'frequency_per_year' => 365,
  ),
  268 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN KIOS Q 2',
    'duration_minutes' => 105,
    'frequency_per_year' => 365,
  ),
  269 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN KIOS Q 3',
    'duration_minutes' => 105,
    'frequency_per_year' => 365,
  ),
  270 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN KIOS Q 4',
    'duration_minutes' => 105,
    'frequency_per_year' => 365,
  ),
  271 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN KIOS Q 5',
    'duration_minutes' => 105,
    'frequency_per_year' => 365,
  ),
  272 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN KIOS Q 6',
    'duration_minutes' => 105,
    'frequency_per_year' => 365,
  ),
  273 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN COPLER',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  274 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN GENERATOR 52 ( GATE OUT BERLIAN )',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  275 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN GENERATOR 53 ( DEPO PTPN )',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  276 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN GENERATOR 54 ( GATE BERLIAN )',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  277 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN GENERATOR 56 ( DEUZT DEPO UNGGUL )',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  278 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN GENERATOR 57( MITSUBISHI DEPO UNGGUL )',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  279 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN GENERATOR 58 ( DEPO UDATIN )',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  280 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE GENERATOR 52 ( GATE OUT BERLIAN )',
    'duration_minutes' => 225,
    'frequency_per_year' => 24,
  ),
  281 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE GENERATOR 53 ( DEPO PTPN )',
    'duration_minutes' => 225,
    'frequency_per_year' => 24,
  ),
  282 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE GENERATOR 54 ( GATE BERLIAN )',
    'duration_minutes' => 225,
    'frequency_per_year' => 24,
  ),
  283 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE GENERATOR 56 ( DEUZT DEPO UNGGUL )',
    'duration_minutes' => 225,
    'frequency_per_year' => 24,
  ),
  284 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE GENERATOR 57( MITSUBISHI DEPO UNGGUL )',
    'duration_minutes' => 225,
    'frequency_per_year' => 24,
  ),
  285 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE GENERATOR 58 ( DEPO UDATIN )',
    'duration_minutes' => 225,
    'frequency_per_year' => 24,
  ),
  286 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN JEMBATAN TIMBANG DEPO MERATUS UDATIN',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  287 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN JEMBATAN TIMBANG 02 GATE IN BERLIAN',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  288 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN JEMBATAN TIMBANG 03 GATE IN BERLIAN',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  289 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN JEMBATAN TIMBANG 04 GATE IN BERLIAN',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  290 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN INCOOMING 20 KV',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  291 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN OUTGOING 20 KV',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  292 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN TRAFO',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  293 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN PANEL LSDP',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  294 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN PANEL ATS',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  295 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN PANEL LMDP1600A',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  296 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN PANEL POWER CAPASITOR',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  297 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN PANEL CAPASITOR BANK',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  298 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN SEGMENT 01',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  299 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN SEGMENT 02',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  300 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN SEGMENT 03',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  301 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PENGECEKAN PORTABEL 01',
    'duration_minutes' => 135,
    'frequency_per_year' => 365,
  ),
  302 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  303 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GRAB SHIP UNLOADER',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  304 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 180,
    'frequency_per_year' => 365,
  ),
  305 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING WIREROPE HOLD',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  306 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING WIREROPE OPEN/CLOSE',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  307 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING WIREROPE SPILLPLATE',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  308 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING WIREROPE GRAB',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  309 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING WIREROPE CATENARY',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  310 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING HOIST PULLEY',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  311 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING LUFFING PULLEY',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  312 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING TROLLEY WHEEL SET',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  313 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING GUIDE TROLLEY WHEEL SET',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  314 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING BEARING BLOCK OF LIFTING DRUM',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  315 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING ELASTIC COUPLING',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  316 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING COUPLING HOLD AND CLOSE DRUM',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  317 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING COUPLING OF BOOM LUFFING DRUM',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  318 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING PEAR SOCKET DAN QUICK RELEASE',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  319 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING GRAB PIN',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  320 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'LUBRICANT CABLE CARRIAGE',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  321 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'LUBRICANT PIN SHAFT AND PIVOTS OF BRAKE',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  322 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE AC',
    'duration_minutes' => 360,
    'frequency_per_year' => 12,
  ),
  323 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CLEANING JUNTION BOX',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  324 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'MEASURING WIREROPE',
    'duration_minutes' => 90,
    'frequency_per_year' => 12,
  ),
  325 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECKING ALL SENSORS',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  326 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECKING CABLE CONNECTION',
    'duration_minutes' => 180,
    'frequency_per_year' => 12,
  ),
  327 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'DPT QUICK RELEASE DAN PEARSOCKET',
    'duration_minutes' => 360,
    'frequency_per_year' => 4,
  ),
  328 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING PULLEY OF GANTRY TRAVEL',
    'duration_minutes' => 360,
    'frequency_per_year' => 4,
  ),
  329 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'LUBRICANT POINT OF LIMIT SWITCH',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  330 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'LUBRICANT POINT OF CABLE REEL',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  331 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'LUBRICANT POINT OF ELEVATOR',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  332 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECKING AND CLEANING CABLE TRAY',
    'duration_minutes' => 360,
    'frequency_per_year' => 4,
  ),
  333 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'LUBRICANT TRAVELLING WHEEL SET OF MAINT. CRANE',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  334 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING CATENARY TROLLEY SHEAVE',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  335 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECKING ENCODER',
    'duration_minutes' => 90,
    'frequency_per_year' => 4,
  ),
  336 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'GREASING WIREROPE BOOM',
    'duration_minutes' => 180,
    'frequency_per_year' => 2,
  ),
  337 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL RAIL BRAKE',
    'duration_minutes' => 90,
    'frequency_per_year' => 1,
  ),
  338 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK CABLE GROUNDING',
    'duration_minutes' => 90,
    'frequency_per_year' => 1,
  ),
  339 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK INSULATION MOTOR',
    'duration_minutes' => 540,
    'frequency_per_year' => 1,
  ),
  340 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHECK VIBRATION MOTOR',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  341 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CLEANING AND CHECKING TRAVO',
    'duration_minutes' => 540,
    'frequency_per_year' => 1,
  ),
  342 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'THRUSTER BRAKE OIL',
    'duration_minutes' => 540,
    'frequency_per_year' => 1,
  ),
  343 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE FILTER OIL HOLD AND OPEN-CLOSE',
    'duration_minutes' => 90,
    'frequency_per_year' => 1,
  ),
  344 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL CYLINDER WINDWALL',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  345 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL CYLINDER HOPPER DOOR',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  346 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL EMERGENCY BRAKE BOOM',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  347 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX HOLD',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  348 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX OPEN CLOSE',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  349 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX FOR GANTRY TRAVEL',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  350 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX FOR MAINTENANCE CRANE TRAVEL',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  351 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX FOR CABIN TRAVEL',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  352 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX FOR ELEVATOR',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  353 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX FOR BOOM HOIST',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  354 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX FOR SPILLPLATE',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  355 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX FOR BELT FEEDER',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  356 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX FOR CABLE REEL',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  357 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX EMERGENCY OPEN-CLOSE',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  358 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX EMERGENCY HOLD',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  359 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX EMERGENCY TROLLEY',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  360 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE OIL GEARBOX EMERGENCY BOOM',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  361 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE WIREROPE GRAB',
    'duration_minutes' => 540,
    'frequency_per_year' => 4,
  ),
  362 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE WIREROPE OPEN-CLOSE',
    'duration_minutes' => 720,
    'frequency_per_year' => 2,
  ),
  363 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE WIREROPE HOLD',
    'duration_minutes' => 720,
    'frequency_per_year' => 2,
  ),
  364 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'CHANGE WIREROPE BOOM',
    'duration_minutes' => 1650,
    'frequency_per_year' => 2,
  ),
  365 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'INSPECTION 3 MONTH CC',
    'duration_minutes' => 360,
    'frequency_per_year' => 4,
  ),
  366 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'INSPECTION 1 MONTH CC',
    'duration_minutes' => 360,
    'frequency_per_year' => 12,
  ),
  367 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'INSPECTION 6 MONTH CC',
    'duration_minutes' => 360,
    'frequency_per_year' => 2,
  ),
  368 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  369 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'LUFFING CRANE ENGINE',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  370 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  371 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 1 MONTH',
    'duration_minutes' => 450,
    'frequency_per_year' => 12,
  ),
  372 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 2 MONTH',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  373 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 3 MONTH',
    'duration_minutes' => 1710,
    'frequency_per_year' => 4,
  ),
  374 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 6 MONTH',
    'duration_minutes' => 1012.5,
    'frequency_per_year' => 2,
  ),
  375 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 1 YEAR',
    'duration_minutes' => 1057.5,
    'frequency_per_year' => 1,
  ),
  376 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'OIL ANALYSYS',
    'duration_minutes' => 112.5,
    'frequency_per_year' => 1,
  ),
  377 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE ENGINE 250',
    'duration_minutes' => 97.5,
    'frequency_per_year' => 32,
  ),
  378 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE ENGINE 500',
    'duration_minutes' => 210,
    'frequency_per_year' => 16,
  ),
  379 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE ENGINE 1000',
    'duration_minutes' => 232.5,
    'frequency_per_year' => 8,
  ),
  380 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'SERVICE ENGINE 2000',
    'duration_minutes' => 577.5,
    'frequency_per_year' => 4,
  ),
  381 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  382 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'LUFFING CRANE ELEKTRIK',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  383 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365,
  ),
  384 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 1 MONTH',
    'duration_minutes' => 450,
    'frequency_per_year' => 12,
  ),
  385 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 2 MONTH',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  386 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 3 MONTH',
    'duration_minutes' => 1710,
    'frequency_per_year' => 4,
  ),
  387 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 6 MONTH',
    'duration_minutes' => 1012.5,
    'frequency_per_year' => 2,
  ),
  388 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'PM 1 YEAR',
    'duration_minutes' => 1057.5,
    'frequency_per_year' => 1,
  ),
  389 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'OIL ANALYSYS',
    'duration_minutes' => 112.5,
    'frequency_per_year' => 1,
  ),
  390 => 
  array (
    'equipment_name' => 'EH',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
  391 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'DAILY INSPECTION ARTG',
    'duration_minutes' => 150,
    'frequency_per_year' => 365,
  ),
  392 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 1 MONTH INSPECTION ARTG',
    'duration_minutes' => 285,
    'frequency_per_year' => 12,
  ),
  393 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 2 MONTH CHECK & GREASE WIREROPE ARTG',
    'duration_minutes' => 180,
    'frequency_per_year' => 6,
  ),
  394 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 2 MONTH CHECK BRAKE',
    'duration_minutes' => 187.5,
    'frequency_per_year' => 6,
  ),
  395 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 3 MONTH GREASE PIN BEARING',
    'duration_minutes' => 360,
    'frequency_per_year' => 4,
  ),
  396 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 3 MONTH INSPECTION',
    'duration_minutes' => 1650,
    'frequency_per_year' => 4,
  ),
  397 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 3 MONTH SERVICE AC',
    'duration_minutes' => 180,
    'frequency_per_year' => 4,
  ),
  398 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 1 YEARLY CHANGE OIL GEARBOX',
    'duration_minutes' => 450,
    'frequency_per_year' => 1,
  ),
  399 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 1 YEAR INSULATION',
    'duration_minutes' => 180,
    'frequency_per_year' => 1,
  ),
  400 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 1 YEARLY REPLACE WIREROPE',
    'duration_minutes' => 720,
    'frequency_per_year' => 1,
  ),
  401 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'PM 1 YEAR INSULATION MAIN POWER SUPPLY',
    'duration_minutes' => 570,
    'frequency_per_year' => 1,
  ),
  402 => 
  array (
    'equipment_name' => 'ARTG',
    'activity_name' => 'TOTAL ACTIVITY (JAM/TAHUN)',
    'duration_minutes' => 0,
    'frequency_per_year' => 0,
  ),
);
        
        foreach ($jobPlans as $jp) {
            $eqModel = EquipmentType::where('name', 'LIKE', '%' . $jp['equipment_name'] . '%')
                        ->orWhere('code', $jp['equipment_name'])->first();
            
            if ($eqModel) {
                $eqModel->jobPlans()->updateOrCreate(
                    ['activity_name' => $jp['activity_name']],
                    [
                        'duration_minutes' => $jp['duration_minutes'],
                        'frequency_per_year' => $jp['frequency_per_year'],
                        'total_hours_per_year' => ($jp['duration_minutes'] / 60) * $jp['frequency_per_year']
                    ]
                );
            }
        }
    }
}