<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteClass;
use App\Models\EquipmentCategoryBaseline;
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

        // 3. Seed Equipment Types (Exported clean data from DB)
        $equipments = array (
  0 => 
  array (
    'name' => 'GRAB SHIP UNLOADER',
    'code' => 'GSU',
    'weight' => 83,
    'category' => 'Crane',
  ),
  1 => 
  array (
    'name' => 'CONTAINER CRANE',
    'code' => 'CC',
    'weight' => 77,
    'category' => 'Crane',
  ),
  2 => 
  array (
    'name' => 'HARBOUR MOBILE CRANE',
    'code' => 'HMC',
    'weight' => 49,
    'category' => 'Crane',
  ),
  3 => 
  array (
    'name' => 'HARBOUR PORTAL CRANE',
    'code' => 'HPC',
    'weight' => 49,
    'category' => 'Crane',
  ),
  4 => 
  array (
    'name' => 'LUFFING CRANE',
    'code' => 'LC',
    'weight' => 45,
    'category' => 'Crane',
  ),
  5 => 
  array (
    'name' => 'GANTRY JIB CRANE',
    'code' => 'GJC',
    'weight' => 45,
    'category' => 'Crane',
  ),
  6 => 
  array (
    'name' => 'MOBILE CRANE',
    'code' => 'MCR',
    'weight' => 24,
    'category' => 'Crane',
  ),
  7 => 
  array (
    'name' => 'FIXED CRANE',
    'code' => 'FCR',
    'weight' => 38,
    'category' => 'Crane',
  ),
  8 => 
  array (
    'name' => 'AUTOMATE RUBBER TYRED GANTRY',
    'code' => 'ARTG',
    'weight' => 65,
    'category' => 'Crane',
  ),
  9 => 
  array (
    'name' => 'AUTOMATED STACKING CRANE',
    'code' => 'ASC',
    'weight' => 65,
    'category' => 'Crane',
  ),
  10 => 
  array (
    'name' => 'RUBBER TYRED GANTRY',
    'code' => 'RTG',
    'weight' => 41,
    'category' => 'Crane',
  ),
  11 => 
  array (
    'name' => 'RAIL MOUNTED GANTRY CRANE',
    'code' => 'RMGC',
    'weight' => 65,
    'category' => 'Crane',
  ),
  12 => 
  array (
    'name' => 'STRADDLE CARRIER',
    'code' => 'SC',
    'weight' => 35,
    'category' => 'Crane',
  ),
  13 => 
  array (
    'name' => 'REACH STACKER',
    'code' => 'RS',
    'weight' => 23,
    'category' => 'Mobile Equipment',
  ),
  14 => 
  array (
    'name' => 'SIDE LOADER',
    'code' => 'SLD',
    'weight' => 18,
    'category' => 'Mobile Equipment',
  ),
  15 => 
  array (
    'name' => 'FORKLIFT 25 T',
    'code' => 'FL 25T',
    'weight' => 11,
    'category' => 'Mobile Equipment',
  ),
  16 => 
  array (
    'name' => 'FORKLIFT 16 T',
    'code' => 'FL 16T',
    'weight' => 10,
    'category' => 'Mobile Equipment',
  ),
  17 => 
  array (
    'name' => 'FORKLIFT 15 T',
    'code' => 'FL 15T',
    'weight' => 9,
    'category' => 'Mobile Equipment',
  ),
  18 => 
  array (
    'name' => 'FORKLIFT 10 T',
    'code' => 'FL 10T',
    'weight' => 8,
    'category' => 'Mobile Equipment',
  ),
  19 => 
  array (
    'name' => 'FORKLIFT 7 T',
    'code' => 'FL 7T',
    'weight' => 7,
    'category' => 'Mobile Equipment',
  ),
  20 => 
  array (
    'name' => 'FORKLIFT 5 T',
    'code' => 'FL 5T',
    'weight' => 6,
    'category' => 'Mobile Equipment',
  ),
  21 => 
  array (
    'name' => 'FORKLIFT 3 T',
    'code' => 'FL 3T',
    'weight' => 5,
    'category' => 'Mobile Equipment',
  ),
  22 => 
  array (
    'name' => 'FORKLIFT 2.5 T',
    'code' => 'FL 2.5T',
    'weight' => 5,
    'category' => 'Mobile Equipment',
  ),
  23 => 
  array (
    'name' => 'CONVEYOR',
    'code' => 'CVY',
    'weight' => 53,
    'category' => 'Mobile Equipment',
  ),
  24 => 
  array (
    'name' => 'TERMINAL TRACTOR',
    'code' => 'TT',
    'weight' => 14,
    'category' => 'Mobile Equipment',
  ),
  25 => 
  array (
    'name' => 'HEAD TRUCK',
    'code' => 'HT',
    'weight' => 9,
    'category' => 'Mobile Equipment',
  ),
  26 => 
  array (
    'name' => 'CHASSIS',
    'code' => 'CHS',
    'weight' => 2,
    'category' => 'Mobile Equipment',
  ),
  27 => 
  array (
    'name' => 'TRONTON',
    'code' => 'TRN',
    'weight' => 11,
    'category' => 'Mobile Equipment',
  ),
  28 => 
  array (
    'name' => 'EXCAVATOR',
    'code' => 'EXCA',
    'weight' => 15,
    'category' => 'Mobile Equipment',
  ),
  29 => 
  array (
    'name' => 'BULLDOZER',
    'code' => 'BLD',
    'weight' => 11,
    'category' => 'Mobile Equipment',
  ),
  30 => 
  array (
    'name' => 'WHEEL LOADER',
    'code' => 'WLD',
    'weight' => 18,
    'category' => 'Mobile Equipment',
  ),
  31 => 
  array (
    'name' => 'DUMP TRUCK',
    'code' => 'DT',
    'weight' => 11,
    'category' => 'Mobile Equipment',
  ),
  32 => 
  array (
    'name' => 'TRANSLIFTER',
    'code' => 'TLFR',
    'weight' => 3,
    'category' => 'Mobile Equipment',
  ),
  33 => 
  array (
    'name' => 'LOWBED',
    'code' => 'LB',
    'weight' => 3,
    'category' => 'Mobile Equipment',
  ),
  34 => 
  array (
    'name' => 'JEMBATAN TIMBANG',
    'code' => 'JT',
    'weight' => 1,
    'category' => 'Others',
  ),
  35 => 
  array (
    'name' => 'HOPPER',
    'code' => 'HPR',
    'weight' => 3,
    'category' => 'Others',
  ),
  36 => 
  array (
    'name' => 'GRAB',
    'code' => 'GRB',
    'weight' => 3,
    'category' => 'Others',
  ),
  37 => 
  array (
    'name' => 'BUCKET',
    'code' => 'BCT',
    'weight' => 1,
    'category' => 'Others',
  ),
  38 => 
  array (
    'name' => 'RAMPDOOR',
    'code' => 'RDR',
    'weight' => 1,
    'category' => 'Others',
  ),
  39 => 
  array (
    'name' => 'Mobil PMK/Tangga/sweeper',
    'code' => 'PMK',
    'weight' => 1,
    'category' => 'Others',
  ),
  40 => 
  array (
    'name' => 'GENSET 2000',
    'code' => 'GST 2000',
    'weight' => 1,
    'category' => 'Others',
  ),
  41 => 
  array (
    'name' => 'GENSET 1500',
    'code' => 'GST 1500',
    'weight' => 1,
    'category' => 'Others',
  ),
  42 => 
  array (
    'name' => 'GENSET 1000',
    'code' => 'GST 1000',
    'weight' => 1,
    'category' => 'Others',
  ),
  43 => 
  array (
    'name' => 'POMPA CPO',
    'code' => 'PMP',
    'weight' => 1,
    'category' => 'Others',
  ),
);

        foreach ($equipments as $eq) {
            $catKey = strtolower($eq['category']);
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

        // 4. Seed Job Plans (Exported clean data from DB)
        $jobPlans = array (
  0 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 365.0,
  ),
  1 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Wirerope Hold',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  2 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Wirerope Open/Close',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  3 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Wirerope Spillplate',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  4 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Wirerope Grab',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  5 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Wirerope Catenary',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  6 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Hoist Pulley',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  7 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Luffing Pulley',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  8 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Trolley Wheel Set',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  9 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Guide Trolley Wheel Set',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  10 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Bearing Block of Lifting Drum',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  11 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Elastic Coupling',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  12 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Coupling Hold and Close Drum',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  13 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Coupling of Boom Luffing Drum',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  14 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Pear Socket Dan Quick Release',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  15 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Grab Pin',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  16 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Lubricant Cable Carriage',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  17 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Lubricant Pin Shaft and Pivots of Brake',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  18 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Service Ac',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 12.0,
  ),
  19 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Cleaning Juntion Box',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  20 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Measuring Wire rope',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 12.0,
  ),
  21 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Checking All Sensors',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  22 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Checking Cable Connection',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  23 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Dpt Quick Release Dan Pear socket',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 4.0,
  ),
  24 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Pulley Of Gantry Travel',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 4.0,
  ),
  25 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Lubricant Point Of Limit Switch',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 4.0,
  ),
  26 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Lubricant Point Of Cable Reel',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 4.0,
  ),
  27 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Lubricant Point Of Elevator',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 4.0,
  ),
  28 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Checking And Cleaning Cable Tray',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 4.0,
  ),
  29 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Lubricant Travelling Wheel Set Of Maint. Crane',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 4.0,
  ),
  30 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Catenary Trolley Sheave',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 4.0,
  ),
  31 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Checking Encoder',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 4.0,
  ),
  32 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Greasing Wire Rope Boom',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 2.0,
  ),
  33 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Rail Brake',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 1.0,
  ),
  34 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Check Cable Grounding',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 1.0,
  ),
  35 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Check Insulation Motor',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  36 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Check Vibration Motor',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  37 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Cleaning And Checking Travo',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  38 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Thruster Brake Oil',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  39 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Filter Oil Hold And Open-Close',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 1.0,
  ),
  40 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Cylinder Windwall',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  41 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Cylinder Hopper Door',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  42 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Emergency Brake Boom',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  43 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox Hold',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  44 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox Open Close',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  45 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox For Gantry Travel',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  46 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox For Maintenance Crane Travel',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  47 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox For Cabin Travel',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  48 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox For Elevator',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  49 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox For Boom Hoist',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  50 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox For Spillplate',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  51 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox For Belt Feeder',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  52 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox For Cable Reel',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  53 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox Emergency Open-Close',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  54 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox Emergency Hold',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  55 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox Emergency Trolley',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  56 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Oil Gearbox Emergency Boom',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  57 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Wirerope Grab',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 4.0,
  ),
  58 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Wirerope Open-Close',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 2.0,
  ),
  59 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Wirerope Hold',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 2.0,
  ),
  60 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Change Wirerope Boom',
    'duration_minutes' => 1650.0,
    'frequency_per_year' => 2.0,
  ),
  61 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Inspection 3 Month CC',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 4.0,
  ),
  62 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Inspection 1 Month CC',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 12.0,
  ),
  63 => 
  array (
    'equipment_code' => 'GSU',
    'activity_name' => 'Inspection 6 Month CC',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 2.0,
  ),
  64 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'DAILY INSPECTION CC',
    'duration_minutes' => 135.0,
    'frequency_per_year' => 365.0,
  ),
  65 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE WIREROPE HOIST CC',
    'duration_minutes' => 6120.0,
    'frequency_per_year' => 1.0,
  ),
  66 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE SPREADER HYDRAULIC OIL CC',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 6.0,
  ),
  67 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE WIREROPE BOOM CC',
    'duration_minutes' => 2160.0,
    'frequency_per_year' => 1.0,
  ),
  68 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING WIREROPE BOOM CC',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 6.0,
  ),
  69 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING WIREROPE HOIST CC',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  70 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING BEARING ROPESHAVE HOIST CC',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 6.0,
  ),
  71 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING CABLE BASKET SPREADER CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 6.0,
  ),
  72 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING SLIDING PAD GEAR BOX FLIPPER DAN TWISTLOCK CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 6.0,
  ),
  73 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING BEARING ROPESHAVE BOOM CC',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 4.0,
  ),
  74 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING WHEEL BEARING GANTRY CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 4.0,
  ),
  75 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING PIN BOOM CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 4.0,
  ),
  76 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE GEAR BOX OIL HOIST CC',
    'duration_minutes' => 1440.0,
    'frequency_per_year' => 1.0,
  ),
  77 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE GEAR BOX OIL BOOM CC',
    'duration_minutes' => 1440.0,
    'frequency_per_year' => 1.0,
  ),
  78 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING RAIL AND GEAR MAN LIFT CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 12.0,
  ),
  79 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING MOTOR DC CC',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  80 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'INSPECTION 3 MONTH CC',
    'duration_minutes' => 382.5,
    'frequency_per_year' => 4.0,
  ),
  81 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'INSPECTION 1 MONTH CC',
    'duration_minutes' => 315.0,
    'frequency_per_year' => 12.0,
  ),
  82 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'INSPECTION 6 MONTH CC',
    'duration_minutes' => 427.5,
    'frequency_per_year' => 2.0,
  ),
  83 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING DRUM AND COUPLING HOIST CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 6.0,
  ),
  84 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING DRUM AND COUPLING TROLLEY CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 6.0,
  ),
  85 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING ROPESHAVE TROLLEY CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 6.0,
  ),
  86 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'GREASING WHELL BEARING TROLLEY CC',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 6.0,
  ),
  87 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE EMERGENCY BOOM HYDRAULIC OIL CC',
    'duration_minutes' => 1440.0,
    'frequency_per_year' => 2.0,
  ),
  88 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'SERVICE AIR CONDITIONER CC',
    'duration_minutes' => 1620.0,
    'frequency_per_year' => 4.0,
  ),
  89 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHECK CARBON BRUSH MOTOR HOIST CC',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 12.0,
  ),
  90 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHECK CARBON BRUSH MOTOR TROLLEY CC',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 12.0,
  ),
  91 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHECK CARBON BRUSH MOTOR GANTRY CC',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 4.0,
  ),
  92 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHECK CARBON BRUSH MOTOR BOOM CC',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 4.0,
  ),
  93 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'INSULATION MOTOR HOIST CC',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 1.0,
  ),
  94 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'INSULATION MOTOR BOOM CC',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 1.0,
  ),
  95 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'INSULATION MOTOR TROLLEY CC',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 1.0,
  ),
  96 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'INSULATION MOTOR GANTRY CC',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 1.0,
  ),
  97 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE TRUSTER BRAKE OIL CC',
    'duration_minutes' => 1440.0,
    'frequency_per_year' => 1.0,
  ),
  98 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE GEAR BOX OIL GANTRY CC',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  99 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHANGE GEAR BOX OIL TROLLEY CC',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  100 => 
  array (
    'equipment_code' => 'CC',
    'activity_name' => 'CHECK CONDITION HEAD BLOCK, PIN, SPLITH PEN, BUSHING, DRUM CABLE AND STRUCTURE (DPT) UNIT CC',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 2.0,
  ),
  101 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 365.0,
  ),
  102 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'SERVICE 250 JAM GOTTWALD',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  103 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'SERVICE 500H GOTTWALD',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 3.0,
  ),
  104 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'SERVICE 1000H GOTTWALD',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 3.0,
  ),
  105 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 4.0,
  ),
  106 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'CHANGE WIREROPE GOTTWALD 4406',
    'duration_minutes' => 2400.0,
    'frequency_per_year' => 1.0,
  ),
  107 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'CHANGE OIL HYDRAULIC GOTTWALD',
    'duration_minutes' => 735.0,
    'frequency_per_year' => 1.0,
  ),
  108 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION 1 MONTH',
    'duration_minutes' => 135.0,
    'frequency_per_year' => 12.0,
  ),
  109 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION 1 MONTH & 2 MONTH',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  110 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  111 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH & 2 MONTH',
    'duration_minutes' => 630.0,
    'frequency_per_year' => 6.0,
  ),
  112 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH & 3 MONTH',
    'duration_minutes' => 690.0,
    'frequency_per_year' => 3.0,
  ),
  113 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH, 2 MONTH, 3 MONTH',
    'duration_minutes' => 735.0,
    'frequency_per_year' => 2.0,
  ),
  114 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'CHANGE OIL HOIST REDUCTION GEAR UNIT',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  115 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'CHANGE OIL SLEWING REDUCTION GEAR UNIT',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  116 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'CHANGE OIL AXLE DIFFERENTIAL AND WHEEL HUB GEARBOX',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  117 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'CHANGE OIL REDUCTION GEAR SWIVEL',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  118 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION DIESEL ENGINE 6000 JAM',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 1.0,
  ),
  119 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR HOIST 6000 JAM',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  120 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR SLEWING 6000 JAM',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  121 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR PUMP 6000 JAM',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  122 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR CABLE REEL SPREADER',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  123 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION MOTOR EXTRA POWER',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  124 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION SPREADER',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  125 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INSPECTION HOOK',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  126 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'CHANGE OIL GEARBOX CABLE REEL SPREADER',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  127 => 
  array (
    'equipment_code' => 'HMC',
    'activity_name' => 'INPECTION TRAVO EXTRA POWER',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  128 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 365.0,
  ),
  129 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'SERVICE 250 JAM GOTTWALD',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  130 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'SERVICE 500H GOTTWALD',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 3.0,
  ),
  131 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'SERVICE 1000H GOTTWALD',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 3.0,
  ),
  132 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 4.0,
  ),
  133 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'CHANGE WIREROPE GOTTWALD 4406',
    'duration_minutes' => 2400.0,
    'frequency_per_year' => 1.0,
  ),
  134 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'CHANGE OIL HYDRAULIC GOTTWALD',
    'duration_minutes' => 735.0,
    'frequency_per_year' => 1.0,
  ),
  135 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION 1 MONTH',
    'duration_minutes' => 135.0,
    'frequency_per_year' => 12.0,
  ),
  136 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION 1 MONTH & 2 MONTH',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  137 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  138 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH & 2 MONTH',
    'duration_minutes' => 630.0,
    'frequency_per_year' => 6.0,
  ),
  139 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH & 3 MONTH',
    'duration_minutes' => 690.0,
    'frequency_per_year' => 3.0,
  ),
  140 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'PREVENTIVE MAINTENANCE 1 MONTH, 2 MONTH, 3 MONTH',
    'duration_minutes' => 735.0,
    'frequency_per_year' => 2.0,
  ),
  141 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'CHANGE OIL HOIST REDUCTION GEAR UNIT',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  142 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'CHANGE OIL SLEWING REDUCTION GEAR UNIT',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  143 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'CHANGE OIL AXLE DIFFERENTIAL AND WHEEL HUB GEARBOX',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  144 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'CHANGE OIL REDUCTION GEAR SWIVEL',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 2.0,
  ),
  145 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION DIESEL ENGINE 6000 JAM',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 1.0,
  ),
  146 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION MOTOR HOIST 6000 JAM',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  147 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION MOTOR SLEWING 6000 JAM',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  148 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION MOTOR PUMP 6000 JAM',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  149 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION MOTOR CABLE REEL SPREADER',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  150 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION MOTOR EXTRA POWER',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  151 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION SPREADER',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  152 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INSPECTION HOOK',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 1.0,
  ),
  153 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'CHANGE OIL GEARBOX CABLE REEL SPREADER',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  154 => 
  array (
    'equipment_code' => 'HPC',
    'activity_name' => 'INPECTION TRAVO EXTRA POWER',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  155 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  156 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'PM 1 MONTH',
    'duration_minutes' => 450.0,
    'frequency_per_year' => 12.0,
  ),
  157 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'PM 2 MONTH',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  158 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'PM 3 MONTH',
    'duration_minutes' => 1710.0,
    'frequency_per_year' => 4.0,
  ),
  159 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'PM 6 MONTH',
    'duration_minutes' => 1012.5,
    'frequency_per_year' => 2.0,
  ),
  160 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'PM 1 YEAR',
    'duration_minutes' => 1057.5,
    'frequency_per_year' => 1.0,
  ),
  161 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'OIL ANALYSYS',
    'duration_minutes' => 112.5,
    'frequency_per_year' => 1.0,
  ),
  162 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'SERVICE ENGINE 250',
    'duration_minutes' => 390.0,
    'frequency_per_year' => 8.0,
  ),
  163 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'SERVICE ENGINE 500',
    'duration_minutes' => 840.0,
    'frequency_per_year' => 4.0,
  ),
  164 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'SERVICE ENGINE 1000',
    'duration_minutes' => 930.0,
    'frequency_per_year' => 2.0,
  ),
  165 => 
  array (
    'equipment_code' => 'LC',
    'activity_name' => 'SERVICE ENGINE 2000',
    'duration_minutes' => 2310.0,
    'frequency_per_year' => 1.0,
  ),
  166 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  167 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'PM 1 MONTH',
    'duration_minutes' => 450.0,
    'frequency_per_year' => 12.0,
  ),
  168 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'PM 2 MONTH',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  169 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'PM 3 MONTH',
    'duration_minutes' => 1710.0,
    'frequency_per_year' => 4.0,
  ),
  170 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'PM 6 MONTH',
    'duration_minutes' => 1012.5,
    'frequency_per_year' => 2.0,
  ),
  171 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'PM 1 YEAR',
    'duration_minutes' => 1057.5,
    'frequency_per_year' => 1.0,
  ),
  172 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'OIL ANALYSYS',
    'duration_minutes' => 112.5,
    'frequency_per_year' => 1.0,
  ),
  173 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'SERVICE ENGINE 250',
    'duration_minutes' => 390.0,
    'frequency_per_year' => 8.0,
  ),
  174 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'SERVICE ENGINE 500',
    'duration_minutes' => 840.0,
    'frequency_per_year' => 4.0,
  ),
  175 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'SERVICE ENGINE 1000',
    'duration_minutes' => 930.0,
    'frequency_per_year' => 2.0,
  ),
  176 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'SERVICE ENGINE 2000',
    'duration_minutes' => 2310.0,
    'frequency_per_year' => 1.0,
  ),
  177 => 
  array (
    'equipment_code' => 'GJC',
    'activity_name' => 'CHANGE WIREROPE',
    'duration_minutes' => 2400.0,
    'frequency_per_year' => 1.0,
  ),
  178 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  179 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'PM 1 MONTH',
    'duration_minutes' => 450.0,
    'frequency_per_year' => 12.0,
  ),
  180 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'PM 2 MONTH',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  181 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'PM 3 MONTH',
    'duration_minutes' => 1710.0,
    'frequency_per_year' => 4.0,
  ),
  182 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'PM 6 MONTH',
    'duration_minutes' => 1012.5,
    'frequency_per_year' => 2.0,
  ),
  183 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'PM 1 YEAR',
    'duration_minutes' => 1057.5,
    'frequency_per_year' => 1.0,
  ),
  184 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'OIL ANALYSYS',
    'duration_minutes' => 112.5,
    'frequency_per_year' => 1.0,
  ),
  185 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'SERVICE ENGINE 250',
    'duration_minutes' => 390.0,
    'frequency_per_year' => 8.0,
  ),
  186 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'SERVICE ENGINE 500',
    'duration_minutes' => 840.0,
    'frequency_per_year' => 4.0,
  ),
  187 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'SERVICE ENGINE 1000',
    'duration_minutes' => 930.0,
    'frequency_per_year' => 2.0,
  ),
  188 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'SERVICE ENGINE 2000',
    'duration_minutes' => 2310.0,
    'frequency_per_year' => 1.0,
  ),
  189 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 4.0,
  ),
  190 => 
  array (
    'equipment_code' => 'MCR',
    'activity_name' => 'CHANGE WIREROPE',
    'duration_minutes' => 2400.0,
    'frequency_per_year' => 1.0,
  ),
  191 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'DAILY INSPECTION FIXED CRANE',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 365.0,
  ),
  192 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'INSPECTION 1 MONTH FIXED CRANE',
    'duration_minutes' => 195.0,
    'frequency_per_year' => 12.0,
  ),
  193 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'MEGGER MOTOR SLEWING FIXED CRANE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  194 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'MEGGER MOTOR LUFFING FIXED CRANE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  195 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'MEGGER MOTOR HOIST FIXED CRANE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  196 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'GREASING WIREROPE HOIST & LUFFING FIXED CRANE',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 12.0,
  ),
  197 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'GREASING ALL BEARING FIXED CRANE',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 12.0,
  ),
  198 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'CHANGE GEARBOX HOIST OIL FIXED CRANE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  199 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'CHANGE GEARBOX LUFFING OIL FIXED CRANE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  200 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'CHANGE GEARBOX SLEWING OIL FIXED CRANE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  201 => 
  array (
    'equipment_code' => 'FCR',
    'activity_name' => 'CHANGE REDUCER OIL FIXED CRANE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  202 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'DAILY INSPECTION ARTG',
    'duration_minutes' => 150.0,
    'frequency_per_year' => 365.0,
  ),
  203 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 1 MONTH INSPECTION ARTG',
    'duration_minutes' => 285.0,
    'frequency_per_year' => 12.0,
  ),
  204 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 2 MONTH CHECK & GREASE WIREROPE ARTG',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  205 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 2 MONTH CHECK BRAKE',
    'duration_minutes' => 187.5,
    'frequency_per_year' => 6.0,
  ),
  206 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 3 MONTH GREASE PIN BEARING',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 4.0,
  ),
  207 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 3 MONTH INSPECTION',
    'duration_minutes' => 1650.0,
    'frequency_per_year' => 4.0,
  ),
  208 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 3 MONTH SERVICE AC',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  209 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 1 YEARLY CHANGE OIL GEARBOX',
    'duration_minutes' => 450.0,
    'frequency_per_year' => 1.0,
  ),
  210 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 1 YEAR INSULATION',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  211 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 1 YEARLY REPLACE WIREROPE',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  212 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'PM 1 YEAR INSULATION MAIN POWER SUPPLY',
    'duration_minutes' => 570.0,
    'frequency_per_year' => 1.0,
  ),
  213 => 
  array (
    'equipment_code' => 'ARTG',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 6.0,
  ),
  214 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'DAILY INSPECTION ARTG',
    'duration_minutes' => 150.0,
    'frequency_per_year' => 365.0,
  ),
  215 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 1 MONTH INSPECTION ARTG',
    'duration_minutes' => 285.0,
    'frequency_per_year' => 12.0,
  ),
  216 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 2 MONTH CHECK & GREASE WIREROPE ARTG',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  217 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 2 MONTH CHECK BRAKE',
    'duration_minutes' => 187.5,
    'frequency_per_year' => 6.0,
  ),
  218 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 3 MONTH GREASE PIN BEARING',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 4.0,
  ),
  219 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 3 MONTH INSPECTION',
    'duration_minutes' => 1650.0,
    'frequency_per_year' => 4.0,
  ),
  220 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 3 MONTH SERVICE AC',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  221 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 1 YEARLY CHANGE OIL GEARBOX',
    'duration_minutes' => 450.0,
    'frequency_per_year' => 1.0,
  ),
  222 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 1 YEAR INSULATION',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 1.0,
  ),
  223 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 1 YEARLY REPLACE WIREROPE',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  224 => 
  array (
    'equipment_code' => 'ASC',
    'activity_name' => 'PM 1 YEAR INSULATION MAIN POWER SUPPLY',
    'duration_minutes' => 570.0,
    'frequency_per_year' => 1.0,
  ),
  225 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'DAILY INSPECTION RTG',
    'duration_minutes' => 120.0,
    'frequency_per_year' => 365.0,
  ),
  226 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'INSPECTION 1 MONTH RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 6.0,
  ),
  227 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'INSPECTION 3 MONTH RTG',
    'duration_minutes' => 630.0,
    'frequency_per_year' => 3.0,
  ),
  228 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'INSPECTION 6 MONTH RTG',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 3.0,
  ),
  229 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'SERVICE 250 JAM RTG ZPMC-TYPE 2506C-E15 TAG2',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  230 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'SERVICE 500 JAM RTG ZPMC-TYPE 2506C-E15 TAG2',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 3.0,
  ),
  231 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'SERVICE 1000 JAM RTG ZPMC-TYPE 2506C-E15 TAG2',
    'duration_minutes' => 2767.5,
    'frequency_per_year' => 3.0,
  ),
  232 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 6.0,
  ),
  233 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE WIREROPE HOIST RTG',
    'duration_minutes' => 9180.0,
    'frequency_per_year' => 1.0,
  ),
  234 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING WIREROPE HOIST RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  235 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING CABLE BASKET SPREADER RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  236 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING SLIDING PAD GEAR BOX FLIPPER DAN TWISTLOCK RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  237 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING MOTOR AC / DC RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 6.0,
  ),
  238 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING OPEN GEAR TROLLEY RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  239 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING SPREADER SYSTEM RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  240 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX HOIST OIL RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 4.0,
  ),
  241 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX TROLLEY OIL RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 4.0,
  ),
  242 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING SKEWING RTG',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 4.0,
  ),
  243 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE HYDRAULIC STEERING GANTRY OIL RTG',
    'duration_minutes' => 742.5,
    'frequency_per_year' => 1.0,
  ),
  244 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX TRIM GEAR OIL RTG',
    'duration_minutes' => 4590.0,
    'frequency_per_year' => 1.0,
  ),
  245 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING ROPESHAVE HOIST RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 12.0,
  ),
  246 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHECK CONDITIONS HEAD BLOCK, TWISLOCK, PIN, SPLITH PEN, BUSHING, DRUM CABLE, STRUCTURE (DPT) RTG',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  247 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING DRUM HOIST AND COUPLING HOIST RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  248 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING CHAIN GANTRY RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  249 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'GREASING BEARING, BOGGIE AND EQUALIZER PIN RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  250 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX DIFFERENTIAL OIL ALL GANTRY RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 3.0,
  ),
  251 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'SERVICE AIR CONDITIONER CABIN OPERATOR DAN ELECTRIC ROOM RTG',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 12.0,
  ),
  252 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE TRUSTHER BRAKE OIL RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 1.0,
  ),
  253 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'INSULATION MOTOR HOIST RTG',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 1.0,
  ),
  254 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'INSULATION GENERATOR RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  255 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'INSULATION MOTOR TROLLEY RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  256 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'INSULATION MOTOR GANTRY RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  257 => 
  array (
    'equipment_code' => 'RTG',
    'activity_name' => 'CHANGE GEAR BOX SKEWING OIL RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 1.0,
  ),
  258 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'DAILY INSPECTION RTG',
    'duration_minutes' => 120.0,
    'frequency_per_year' => 365.0,
  ),
  259 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'INSPECTION 1 MONTH RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 6.0,
  ),
  260 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'INSPECTION 3 MONTH RTG',
    'duration_minutes' => 630.0,
    'frequency_per_year' => 3.0,
  ),
  261 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'INSPECTION 6 MONTH RTG',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 3.0,
  ),
  262 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'SERVICE 250 JAM RTG',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  263 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'SERVICE 500 JAM RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 3.0,
  ),
  264 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'SERVICE 1000 JAM RTG',
    'duration_minutes' => 2767.5,
    'frequency_per_year' => 3.0,
  ),
  265 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHANGE WIREROPE HOIST RTG',
    'duration_minutes' => 9180.0,
    'frequency_per_year' => 1.0,
  ),
  266 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING WIREROPE HOIST RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  267 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING CABLE BASKET SPREADER RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  268 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING SLIDING PAD GEAR BOX FLIPPER DAN TWISTLOCK RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  269 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING MOTOR AC / DC RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 6.0,
  ),
  270 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING OPEN GEAR TROLLEY RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  271 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING SPREADER SYSTEM RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  272 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHANGE GEAR BOX HOIST OIL RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 4.0,
  ),
  273 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHANGE GEAR BOX TROLLEY OIL RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 4.0,
  ),
  274 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING SKEWING RTG',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 4.0,
  ),
  275 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHANGE HYDRAULIC STEERING GANTRY OIL RTG',
    'duration_minutes' => 742.5,
    'frequency_per_year' => 1.0,
  ),
  276 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHANGE GEAR BOX TRIM GEAR OIL RTG',
    'duration_minutes' => 4590.0,
    'frequency_per_year' => 1.0,
  ),
  277 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING ROPESHAVE HOIST RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 12.0,
  ),
  278 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHECK CONDITIONS HEAD BLOCK, TWISLOCK, PIN, SPLITH PEN, BUSHING, DRUM CABLE, STRUCTURE (DPT) RTG',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  279 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING DRUM HOIST AND COUPLING HOIST RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  280 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING CHAIN GANTRY RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  281 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'GREASING BEARING, BOGGIE AND EQUALIZER PIN RTG',
    'duration_minutes' => 405.0,
    'frequency_per_year' => 6.0,
  ),
  282 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHANGE GEAR BOX DIFFERENTIAL OIL ALL GANTRY RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 3.0,
  ),
  283 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'SERVICE AIR CONDITIONER CABIN OPERATOR DAN ELECTRIC ROOM RTG',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 12.0,
  ),
  284 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHANGE TRUSTHER BRAKE OIL RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 1.0,
  ),
  285 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'INSULATION MOTOR HOIST RTG',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 1.0,
  ),
  286 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'INSULATION GENERATOR RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  287 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'INSULATION MOTOR TROLLEY RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  288 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'INSULATION MOTOR GANTRY RTG',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 1.0,
  ),
  289 => 
  array (
    'equipment_code' => 'RMGC',
    'activity_name' => 'CHANGE GEAR BOX SKEWING OIL RTG',
    'duration_minutes' => 1080.0,
    'frequency_per_year' => 1.0,
  ),
  290 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 75.0,
    'frequency_per_year' => 365.0,
  ),
  291 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'SERVICE WEEKLY',
    'duration_minutes' => 135.0,
    'frequency_per_year' => 48.0,
  ),
  292 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'SERVICE 500 JAM',
    'duration_minutes' => 630.0,
    'frequency_per_year' => 4.0,
  ),
  293 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'SERVICE 1000 JAM',
    'duration_minutes' => 630.0,
    'frequency_per_year' => 4.0,
  ),
  294 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'SERVICE 2000 JAM',
    'duration_minutes' => 1440.0,
    'frequency_per_year' => 4.0,
  ),
  295 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'SERVICE 4000 JAM',
    'duration_minutes' => 1620.0,
    'frequency_per_year' => 3.0,
  ),
  296 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'SERVICE 6000 JAM',
    'duration_minutes' => 1710.0,
    'frequency_per_year' => 3.0,
  ),
  297 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'SERVICE 8000 JAM',
    'duration_minutes' => 1620.0,
    'frequency_per_year' => 3.0,
  ),
  298 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'SERVICE 12000 JAM',
    'duration_minutes' => 1800.0,
    'frequency_per_year' => 3.0,
  ),
  299 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'CHANGE WIREROPE HOIST',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 3.0,
  ),
  300 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'GREASING SLIDING PAD SPREADER',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  301 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'GREASING PIN DAN BEARING',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  302 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'CHANGE TRUSTER BRAKE OIL',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  303 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'CHANGE GEAR BOX OIL GANTRY',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 3.0,
  ),
  304 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'CHANGE GEAR BOX OIL HOIST',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  305 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'CHECK CONDITION HEAD BLOCK, PIN, SPLITH PEN, BUSHING, DRUM CABLE AND STRUCTURE (DPT) UNIT',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 3.0,
  ),
  306 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'INSULATION MOTOR HOIST',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  307 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'INSULATION MOTOR GANTRY',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  308 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'INSULATION MOTOR SPREADER',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 3.0,
  ),
  309 => 
  array (
    'equipment_code' => 'SC',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 8.0,
  ),
  310 => 
  array (
    'equipment_code' => 'RS',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 82.5,
    'frequency_per_year' => 365.0,
  ),
  311 => 
  array (
    'equipment_code' => 'RS',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 12.0,
  ),
  312 => 
  array (
    'equipment_code' => 'RS',
    'activity_name' => 'Service 500',
    'duration_minutes' => 1215.0,
    'frequency_per_year' => 6.0,
  ),
  313 => 
  array (
    'equipment_code' => 'RS',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  314 => 
  array (
    'equipment_code' => 'RS',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  315 => 
  array (
    'equipment_code' => 'RS',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  316 => 
  array (
    'equipment_code' => 'SLD',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 82.5,
    'frequency_per_year' => 365.0,
  ),
  317 => 
  array (
    'equipment_code' => 'SLD',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  318 => 
  array (
    'equipment_code' => 'SLD',
    'activity_name' => 'Service 500',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 6.0,
  ),
  319 => 
  array (
    'equipment_code' => 'SLD',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  320 => 
  array (
    'equipment_code' => 'SLD',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  321 => 
  array (
    'equipment_code' => 'SLD',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 24.0,
  ),
  322 => 
  array (
    'equipment_code' => 'FL 25T',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  323 => 
  array (
    'equipment_code' => 'FL 25T',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  324 => 
  array (
    'equipment_code' => 'FL 25T',
    'activity_name' => 'Service 500',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  325 => 
  array (
    'equipment_code' => 'FL 25T',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  326 => 
  array (
    'equipment_code' => 'FL 25T',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  327 => 
  array (
    'equipment_code' => 'FL 25T',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  328 => 
  array (
    'equipment_code' => 'FL 16T',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  329 => 
  array (
    'equipment_code' => 'FL 16T',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  330 => 
  array (
    'equipment_code' => 'FL 16T',
    'activity_name' => 'Service 500',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  331 => 
  array (
    'equipment_code' => 'FL 16T',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  332 => 
  array (
    'equipment_code' => 'FL 16T',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  333 => 
  array (
    'equipment_code' => 'FL 16T',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  334 => 
  array (
    'equipment_code' => 'FL 15T',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  335 => 
  array (
    'equipment_code' => 'FL 15T',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  336 => 
  array (
    'equipment_code' => 'FL 15T',
    'activity_name' => 'Service 500',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  337 => 
  array (
    'equipment_code' => 'FL 15T',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  338 => 
  array (
    'equipment_code' => 'FL 15T',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  339 => 
  array (
    'equipment_code' => 'FL 15T',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  340 => 
  array (
    'equipment_code' => 'FL 10T',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  341 => 
  array (
    'equipment_code' => 'FL 10T',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  342 => 
  array (
    'equipment_code' => 'FL 10T',
    'activity_name' => 'Service 500',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  343 => 
  array (
    'equipment_code' => 'FL 10T',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  344 => 
  array (
    'equipment_code' => 'FL 10T',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  345 => 
  array (
    'equipment_code' => 'FL 10T',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  346 => 
  array (
    'equipment_code' => 'FL 7T',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  347 => 
  array (
    'equipment_code' => 'FL 7T',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  348 => 
  array (
    'equipment_code' => 'FL 7T',
    'activity_name' => 'Service 500',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  349 => 
  array (
    'equipment_code' => 'FL 7T',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  350 => 
  array (
    'equipment_code' => 'FL 7T',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  351 => 
  array (
    'equipment_code' => 'FL 7T',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  352 => 
  array (
    'equipment_code' => 'FL 5T',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  353 => 
  array (
    'equipment_code' => 'FL 5T',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  354 => 
  array (
    'equipment_code' => 'FL 5T',
    'activity_name' => 'Service 500',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  355 => 
  array (
    'equipment_code' => 'FL 5T',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  356 => 
  array (
    'equipment_code' => 'FL 5T',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  357 => 
  array (
    'equipment_code' => 'FL 5T',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  358 => 
  array (
    'equipment_code' => 'FL 3T',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  359 => 
  array (
    'equipment_code' => 'FL 3T',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  360 => 
  array (
    'equipment_code' => 'FL 3T',
    'activity_name' => 'Service 500',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  361 => 
  array (
    'equipment_code' => 'FL 3T',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  362 => 
  array (
    'equipment_code' => 'FL 3T',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  363 => 
  array (
    'equipment_code' => 'FL 3T',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  364 => 
  array (
    'equipment_code' => 'FL 2.5T',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  365 => 
  array (
    'equipment_code' => 'FL 2.5T',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 540.0,
    'frequency_per_year' => 12.0,
  ),
  366 => 
  array (
    'equipment_code' => 'FL 2.5T',
    'activity_name' => 'Service 500',
    'duration_minutes' => 855.0,
    'frequency_per_year' => 6.0,
  ),
  367 => 
  array (
    'equipment_code' => 'FL 2.5T',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 2295.0,
    'frequency_per_year' => 3.0,
  ),
  368 => 
  array (
    'equipment_code' => 'FL 2.5T',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 2700.0,
    'frequency_per_year' => 3.0,
  ),
  369 => 
  array (
    'equipment_code' => 'FL 2.5T',
    'activity_name' => 'Change Tyre',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 12.0,
  ),
  370 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'DAILY INSPECTION EH DCT 80 TAD 760 VE',
    'duration_minutes' => 82.5,
    'frequency_per_year' => 365.0,
  ),
  371 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'GREASING',
    'duration_minutes' => 1440.0,
    'frequency_per_year' => 24.0,
  ),
  372 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'PELUMASAN',
    'duration_minutes' => 1440.0,
    'frequency_per_year' => 24.0,
  ),
  373 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'PENGECEKAN MANLIFT & GREASING REL MANLIFT',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 6.0,
  ),
  374 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'PENGECEKAN SERVICE HOIST',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  375 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CLEANING PANEL AREA',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  376 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CLEANING PANEL OUTDOOR',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 12.0,
  ),
  377 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHECK DUMPER CHUTE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  378 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHECK BUFFER HOPPER',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  379 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHECK SENSOR',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  380 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHECK AND CLEANING SAFETY DEVICE',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  381 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CLEANING & CHECK DRIVE PANEL',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  382 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CLEANING CUBICLE PANEL',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  383 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHECK CABLE TRAY & TERMINATION',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 4.0,
  ),
  384 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CLEANING PC CONTROL ROOM',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 2.0,
  ),
  385 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHANGE GEARBOX OIL BC01',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  386 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHANGE GEARBOX OIL BC02',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  387 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHANGE GEARBOX OIL BC03',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  388 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHANGE GEARBOX OIL TC',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 1.0,
  ),
  389 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'CHECK COUNTER WEIGHT',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 1.0,
  ),
  390 => 
  array (
    'equipment_code' => 'CVY',
    'activity_name' => 'INSULATION MOTOR',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  391 => 
  array (
    'equipment_code' => 'TT',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 365.0,
  ),
  392 => 
  array (
    'equipment_code' => 'TT',
    'activity_name' => 'SERVICE 250 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  393 => 
  array (
    'equipment_code' => 'TT',
    'activity_name' => 'SERVICE 2000 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 2.0,
  ),
  394 => 
  array (
    'equipment_code' => 'TT',
    'activity_name' => 'SERVICE 6000 JAM',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  395 => 
  array (
    'equipment_code' => 'TT',
    'activity_name' => 'SERVICE 1000 JAM',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 2.0,
  ),
  396 => 
  array (
    'equipment_code' => 'TT',
    'activity_name' => 'SERVICE 500 JAM',
    'duration_minutes' => 990.0,
    'frequency_per_year' => 5.0,
  ),
  397 => 
  array (
    'equipment_code' => 'TT',
    'activity_name' => 'SERVICE 750 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  398 => 
  array (
    'equipment_code' => 'TT',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 8.0,
  ),
  399 => 
  array (
    'equipment_code' => 'HT',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 365.0,
  ),
  400 => 
  array (
    'equipment_code' => 'HT',
    'activity_name' => 'SERVICE 250 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  401 => 
  array (
    'equipment_code' => 'HT',
    'activity_name' => 'SERVICE 2000 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 2.0,
  ),
  402 => 
  array (
    'equipment_code' => 'HT',
    'activity_name' => 'SERVICE 6000 JAM',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  403 => 
  array (
    'equipment_code' => 'HT',
    'activity_name' => 'SERVICE 1000 JAM',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 2.0,
  ),
  404 => 
  array (
    'equipment_code' => 'HT',
    'activity_name' => 'SERVICE 500 JAM',
    'duration_minutes' => 990.0,
    'frequency_per_year' => 5.0,
  ),
  405 => 
  array (
    'equipment_code' => 'HT',
    'activity_name' => 'SERVICE 750 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  406 => 
  array (
    'equipment_code' => 'HT',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 8.0,
  ),
  407 => 
  array (
    'equipment_code' => 'CHS',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 365.0,
  ),
  408 => 
  array (
    'equipment_code' => 'CHS',
    'activity_name' => 'SERVICE 250 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  409 => 
  array (
    'equipment_code' => 'CHS',
    'activity_name' => 'SERVICE 2000 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 2.0,
  ),
  410 => 
  array (
    'equipment_code' => 'CHS',
    'activity_name' => 'SERVICE 6000 JAM',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  411 => 
  array (
    'equipment_code' => 'CHS',
    'activity_name' => 'SERVICE 1000 JAM',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 2.0,
  ),
  412 => 
  array (
    'equipment_code' => 'CHS',
    'activity_name' => 'SERVICE 500 JAM',
    'duration_minutes' => 990.0,
    'frequency_per_year' => 5.0,
  ),
  413 => 
  array (
    'equipment_code' => 'CHS',
    'activity_name' => 'SERVICE 750 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  414 => 
  array (
    'equipment_code' => 'CHS',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 8.0,
  ),
  415 => 
  array (
    'equipment_code' => 'TRN',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 365.0,
  ),
  416 => 
  array (
    'equipment_code' => 'TRN',
    'activity_name' => 'SERVICE 250 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  417 => 
  array (
    'equipment_code' => 'TRN',
    'activity_name' => 'SERVICE 2000 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 2.0,
  ),
  418 => 
  array (
    'equipment_code' => 'TRN',
    'activity_name' => 'SERVICE 6000 JAM',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  419 => 
  array (
    'equipment_code' => 'TRN',
    'activity_name' => 'SERVICE 1000 JAM',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 2.0,
  ),
  420 => 
  array (
    'equipment_code' => 'TRN',
    'activity_name' => 'SERVICE 500 JAM',
    'duration_minutes' => 990.0,
    'frequency_per_year' => 5.0,
  ),
  421 => 
  array (
    'equipment_code' => 'TRN',
    'activity_name' => 'SERVICE 750 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  422 => 
  array (
    'equipment_code' => 'TRN',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 8.0,
  ),
  423 => 
  array (
    'equipment_code' => 'EXCA',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 365.0,
  ),
  424 => 
  array (
    'equipment_code' => 'EXCA',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  425 => 
  array (
    'equipment_code' => 'EXCA',
    'activity_name' => 'Service 500',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 6.0,
  ),
  426 => 
  array (
    'equipment_code' => 'EXCA',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 3.0,
  ),
  427 => 
  array (
    'equipment_code' => 'EXCA',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 1530.0,
    'frequency_per_year' => 3.0,
  ),
  428 => 
  array (
    'equipment_code' => 'BLD',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 365.0,
  ),
  429 => 
  array (
    'equipment_code' => 'BLD',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 12.0,
  ),
  430 => 
  array (
    'equipment_code' => 'BLD',
    'activity_name' => 'Service 500',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 6.0,
  ),
  431 => 
  array (
    'equipment_code' => 'BLD',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 3.0,
  ),
  432 => 
  array (
    'equipment_code' => 'BLD',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 1530.0,
    'frequency_per_year' => 3.0,
  ),
  433 => 
  array (
    'equipment_code' => 'WLD',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 52.5,
    'frequency_per_year' => 365.0,
  ),
  434 => 
  array (
    'equipment_code' => 'WLD',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 12.0,
  ),
  435 => 
  array (
    'equipment_code' => 'WLD',
    'activity_name' => 'Service 500',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 6.0,
  ),
  436 => 
  array (
    'equipment_code' => 'WLD',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 3.0,
  ),
  437 => 
  array (
    'equipment_code' => 'WLD',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 990.0,
    'frequency_per_year' => 3.0,
  ),
  438 => 
  array (
    'equipment_code' => 'WLD',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 16.0,
  ),
  439 => 
  array (
    'equipment_code' => 'DT',
    'activity_name' => 'Daily Inspection',
    'duration_minutes' => 52.5,
    'frequency_per_year' => 365.0,
  ),
  440 => 
  array (
    'equipment_code' => 'DT',
    'activity_name' => 'Service 250 & 750',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 12.0,
  ),
  441 => 
  array (
    'equipment_code' => 'DT',
    'activity_name' => 'Service 500',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 6.0,
  ),
  442 => 
  array (
    'equipment_code' => 'DT',
    'activity_name' => 'Service 1000',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 3.0,
  ),
  443 => 
  array (
    'equipment_code' => 'DT',
    'activity_name' => 'Service 2000',
    'duration_minutes' => 990.0,
    'frequency_per_year' => 3.0,
  ),
  444 => 
  array (
    'equipment_code' => 'DT',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 16.0,
  ),
  445 => 
  array (
    'equipment_code' => 'TLFR',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 365.0,
  ),
  446 => 
  array (
    'equipment_code' => 'TLFR',
    'activity_name' => 'SERVICE 250 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  447 => 
  array (
    'equipment_code' => 'TLFR',
    'activity_name' => 'SERVICE 2000 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 2.0,
  ),
  448 => 
  array (
    'equipment_code' => 'TLFR',
    'activity_name' => 'SERVICE 6000 JAM',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  449 => 
  array (
    'equipment_code' => 'TLFR',
    'activity_name' => 'SERVICE 1000 JAM',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 2.0,
  ),
  450 => 
  array (
    'equipment_code' => 'TLFR',
    'activity_name' => 'SERVICE 500 JAM',
    'duration_minutes' => 990.0,
    'frequency_per_year' => 5.0,
  ),
  451 => 
  array (
    'equipment_code' => 'TLFR',
    'activity_name' => 'SERVICE 750 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  452 => 
  array (
    'equipment_code' => 'TLFR',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 8.0,
  ),
  453 => 
  array (
    'equipment_code' => 'LB',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 365.0,
  ),
  454 => 
  array (
    'equipment_code' => 'LB',
    'activity_name' => 'SERVICE 250 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  455 => 
  array (
    'equipment_code' => 'LB',
    'activity_name' => 'SERVICE 2000 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 2.0,
  ),
  456 => 
  array (
    'equipment_code' => 'LB',
    'activity_name' => 'SERVICE 6000 JAM',
    'duration_minutes' => 720.0,
    'frequency_per_year' => 1.0,
  ),
  457 => 
  array (
    'equipment_code' => 'LB',
    'activity_name' => 'SERVICE 1000 JAM',
    'duration_minutes' => 810.0,
    'frequency_per_year' => 2.0,
  ),
  458 => 
  array (
    'equipment_code' => 'LB',
    'activity_name' => 'SERVICE 500 JAM',
    'duration_minutes' => 990.0,
    'frequency_per_year' => 5.0,
  ),
  459 => 
  array (
    'equipment_code' => 'LB',
    'activity_name' => 'SERVICE 750 JAM',
    'duration_minutes' => 360.0,
    'frequency_per_year' => 5.0,
  ),
  460 => 
  array (
    'equipment_code' => 'LB',
    'activity_name' => 'CHANGE TYRE',
    'duration_minutes' => 480.0,
    'frequency_per_year' => 8.0,
  ),
  461 => 
  array (
    'equipment_code' => 'JT',
    'activity_name' => 'PENGECEKAN JEMBATAN TIMBANG',
    'duration_minutes' => 67.5,
    'frequency_per_year' => 365.0,
  ),
  462 => 
  array (
    'equipment_code' => 'HPR',
    'activity_name' => 'GREASING HOPPER',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 12.0,
  ),
  463 => 
  array (
    'equipment_code' => 'HPR',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 25.0,
    'frequency_per_year' => 365.0,
  ),
  464 => 
  array (
    'equipment_code' => 'GRB',
    'activity_name' => 'GREASING GRAB',
    'duration_minutes' => 60.0,
    'frequency_per_year' => 12.0,
  ),
  465 => 
  array (
    'equipment_code' => 'GRB',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 50.0,
    'frequency_per_year' => 365.0,
  ),
  466 => 
  array (
    'equipment_code' => 'BCT',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 52.5,
    'frequency_per_year' => 365.0,
  ),
  467 => 
  array (
    'equipment_code' => 'RDR',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 52.5,
    'frequency_per_year' => 365.0,
  ),
  468 => 
  array (
    'equipment_code' => 'PMK',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 52.5,
    'frequency_per_year' => 365.0,
  ),
  469 => 
  array (
    'equipment_code' => 'GST 2000',
    'activity_name' => 'DAILY INSPECTION GENSET',
    'duration_minutes' => 37.5,
    'frequency_per_year' => 365.0,
  ),
  470 => 
  array (
    'equipment_code' => 'GST 2000',
    'activity_name' => 'SERVICE ENGINE 250 HOURS GENSET',
    'duration_minutes' => 45.0,
    'frequency_per_year' => 4.0,
  ),
  471 => 
  array (
    'equipment_code' => 'GST 2000',
    'activity_name' => 'SERVICE ENGINE 500 HOURS GENSET',
    'duration_minutes' => 120.0,
    'frequency_per_year' => 4.0,
  ),
  472 => 
  array (
    'equipment_code' => 'GST 2000',
    'activity_name' => 'SERVICE ENGINE 1000 HOURS GENSET',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 2.0,
  ),
  473 => 
  array (
    'equipment_code' => 'GST 2000',
    'activity_name' => 'SERVICE ENGINE 2000 HOURS GENSET',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 2.0,
  ),
  474 => 
  array (
    'equipment_code' => 'GST 1500',
    'activity_name' => 'DAILY INSPECTION GENSET',
    'duration_minutes' => 37.5,
    'frequency_per_year' => 365.0,
  ),
  475 => 
  array (
    'equipment_code' => 'GST 1500',
    'activity_name' => 'SERVICE ENGINE 250 HOURS GENSET',
    'duration_minutes' => 45.0,
    'frequency_per_year' => 4.0,
  ),
  476 => 
  array (
    'equipment_code' => 'GST 1500',
    'activity_name' => 'SERVICE ENGINE 500 HOURS GENSET',
    'duration_minutes' => 120.0,
    'frequency_per_year' => 4.0,
  ),
  477 => 
  array (
    'equipment_code' => 'GST 1500',
    'activity_name' => 'SERVICE ENGINE 1000 HOURS GENSET',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 2.0,
  ),
  478 => 
  array (
    'equipment_code' => 'GST 1500',
    'activity_name' => 'SERVICE ENGINE 2000 HOURS GENSET',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 2.0,
  ),
  479 => 
  array (
    'equipment_code' => 'GST 1000',
    'activity_name' => 'DAILY INSPECTION GENSET',
    'duration_minutes' => 37.5,
    'frequency_per_year' => 365.0,
  ),
  480 => 
  array (
    'equipment_code' => 'GST 1000',
    'activity_name' => 'SERVICE ENGINE 250 HOURS GENSET',
    'duration_minutes' => 45.0,
    'frequency_per_year' => 4.0,
  ),
  481 => 
  array (
    'equipment_code' => 'GST 1000',
    'activity_name' => 'SERVICE ENGINE 500 HOURS GENSET',
    'duration_minutes' => 120.0,
    'frequency_per_year' => 4.0,
  ),
  482 => 
  array (
    'equipment_code' => 'GST 1000',
    'activity_name' => 'SERVICE ENGINE 1000 HOURS GENSET',
    'duration_minutes' => 180.0,
    'frequency_per_year' => 2.0,
  ),
  483 => 
  array (
    'equipment_code' => 'GST 1000',
    'activity_name' => 'SERVICE ENGINE 2000 HOURS GENSET',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 2.0,
  ),
  484 => 
  array (
    'equipment_code' => 'PMP',
    'activity_name' => 'SERVICE ENGINE 250 JAM',
    'duration_minutes' => 90.0,
    'frequency_per_year' => 4.0,
  ),
  485 => 
  array (
    'equipment_code' => 'PMP',
    'activity_name' => 'SERVICE ENGINE 500 JAM',
    'duration_minutes' => 135.0,
    'frequency_per_year' => 4.0,
  ),
  486 => 
  array (
    'equipment_code' => 'PMP',
    'activity_name' => 'SERVICE ENGINE 750 JAM',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 2.0,
  ),
  487 => 
  array (
    'equipment_code' => 'PMP',
    'activity_name' => 'SERVICE ENGINE 1000 JAM',
    'duration_minutes' => 270.0,
    'frequency_per_year' => 2.0,
  ),
  488 => 
  array (
    'equipment_code' => 'PMP',
    'activity_name' => 'DAILY INSPECTION',
    'duration_minutes' => 30.0,
    'frequency_per_year' => 365.0,
  ),
);

        foreach ($jobPlans as $jp) {
            $eqModel = EquipmentType::where('code', $jp['equipment_code'])->first();
            
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