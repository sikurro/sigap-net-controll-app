<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NonTechnicalPosition;
use App\Models\SiteClass;

class MatrixSdmSeeder extends Seeder
{
    public function run(): void
    {
        $nonTechnicalMatrix = [
            ['position' => 'SITE MANAGER', 'requirements' => '{"UTAMA":1,"A":1,"B":1,"C":1,"D":1,"E":1}'],
            ['position' => 'SITE COORDINATOR', 'requirements' => '{"UTAMA":4,"A":4,"B":0,"C":0,"D":0,"E":0}'],
            ['position' => 'LEADER NON SHIFT', 'requirements' => '{"UTAMA":4,"A":0,"B":1,"C":1,"D":0,"E":0}'],
            ['position' => 'LEADER SHIFT', 'requirements' => '{"UTAMA":16,"A":4,"B":4,"C":4,"D":4,"E":0}'],
            ['position' => 'KOORDINATOR LOGISTIK', 'requirements' => '{"UTAMA":1,"A":1,"B":1,"C":0,"D":0,"E":0}'],
            ['position' => 'TOTAL FUNGSIONAL', 'requirements' => '{"UTAMA":26,"A":10,"B":7,"C":6,"D":5,"E":1}'],
            ['position' => 'SAFETY OFFICER', 'requirements' => '{"UTAMA":2,"A":2,"B":1,"C":0,"D":0,"E":0}'],
            ['position' => 'ADMIN PENAGIHAN', 'requirements' => '{"UTAMA":1,"A":1,"B":1,"C":0,"D":0,"E":0}'],
            ['position' => 'PLANNER', 'requirements' => '{"UTAMA":4,"A":4,"B":1,"C":1,"D":1,"E":0}'],
            ['position' => 'ADMIN OPERASIONAL', 'requirements' => '{"UTAMA":4,"A":4,"B":1,"C":3,"D":1,"E":1}'],
            ['position' => 'WAREHOUSE', 'requirements' => '{"UTAMA":4,"A":4,"B":4,"C":2,"D":1,"E":0}'],
            ['position' => 'TOTAL NON FUNGSIONAL', 'requirements' => '{"UTAMA":15,"A":15,"B":8,"C":6,"D":3,"E":1}'],
            ['position' => 'TOTAL', 'requirements' => '{"UTAMA":41,"A":25,"B":15,"C":12,"D":8,"E":2}'],
        ];

        $currentCategory = 'fungsional';

        foreach ($nonTechnicalMatrix as $item) {
            $posName = trim($item['position']);
            if (strpos($posName, 'TOTAL') !== false) {
                if ($posName === 'TOTAL FUNGSIONAL') {
                    $currentCategory = 'non-fungsional';
                }
                continue; // skip creating positions for totals
            }

            $position = NonTechnicalPosition::firstOrCreate(
                ['title' => $posName],
                ['category' => $currentCategory]
            );
            
            $reqs = json_decode($item['requirements'], true);
            if ($reqs) {
                foreach ($reqs as $className => $qty) {
                    $siteClass = SiteClass::where('name', $className)->first();
                    if ($siteClass) {
                        \Illuminate\Support\Facades\DB::table('non_technical_requirements')->updateOrInsert(
                            [
                                'site_class_id' => $siteClass->id,
                                'non_technical_position_id' => $position->id
                            ],
                            ['quantity' => $qty, 'created_at' => now(), 'updated_at' => now()]
                        );
                    }
                }
            }
        }
    }
}