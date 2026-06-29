<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;
use App\Models\EquipmentType;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = array (
  0 => 
  array (
    'site_name' => 'TERMINAL PETIKEMAS BANJARMASIN',
    'equipments' => '[{\\"name\\":\\"CONTAINER CRANE\\",\\"quantity\\":5},{\\"name\\":\\"FORKLIFT DIESEL\\",\\"quantity\\":6},{\\"name\\":\\"GENSET\\",\\"quantity\\":5},{\\"name\\":\\"HEAD TRUCK\\",\\"quantity\\":25},{\\"name\\":\\"REACH STACKER\\",\\"quantity\\":6},{\\"name\\":\\"RUBBER TYRE GANTRY\\",\\"quantity\\":12},{\\"name\\":\\"SIDE LOADER\\",\\"quantity\\":3},{\\"name\\":\\"WHEEL LOADER\\",\\"quantity\\":2},{\\"name\\":\\"ERTG\\",\\"quantity\\":4},{\\"name\\":\\"CONTAINER CRANE\\",\\"quantity\\":2},{\\"name\\":\\"FORKLIFT DIESEL\\",\\"quantity\\":2},{\\"name\\":\\"HEAD TRUCK\\",\\"quantity\\":13},{\\"name\\":\\"REACH STACKER\\",\\"quantity\\":2},{\\"name\\":\\"RUBBER TYRE GANTRY\\",\\"quantity\\":5},{\\"name\\":\\"CONTAINER CRANE\\",\\"quantity\\":2},{\\"name\\":\\"FORKLIFT DIESEL\\",\\"quantity\\":1},{\\"name\\":\\"HEAD TRUCK\\",\\"quantity\\":2},{\\"name\\":\\"REACH STACKER\\",\\"quantity\\":3},{\\"name\\":\\"RUBBER TYRE GANTRY\\",\\"quantity\\":4},{\\"name\\":\\"TERMINAL TRACTOR\\",\\"quantity\\":10},{\\"name\\":\\"ERTG\\",\\"quantity\\":1},{\\"name\\":\\"AUTOMATIC RUBBER TYRE GANTRY\\",\\"quantity\\":20},{\\"name\\":\\"CONTAINER CRANE\\",\\"quantity\\":10},{\\"name\\":\\"FORKLIFT DIESEL\\",\\"quantity\\":1},{\\"name\\":\\"FORKLIFT ELECTRIC\\",\\"quantity\\":9},{\\"name\\":\\"HEAD TRUCK\\",\\"quantity\\":57},{\\"name\\":\\"MOBIL PMK\\",\\"quantity\\":1},{\\"name\\":\\"REACH STACKER\\",\\"quantity\\":3},{\\"name\\":\\"RUBBER TYRE GANTRY\\",\\"quantity\\":7},{\\"name\\":\\"SIDE LOADER\\",\\"quantity\\":2},{\\"name\\":\\"CONTAINER CRANE\\",\\"quantity\\":11},{\\"name\\":\\"FORKLIFT\\",\\"quantity\\":19},{\\"name\\":\\"HEAD TRUCK\\",\\"quantity\\":105},{\\"name\\":\\"REACH STACKER\\",\\"quantity\\":6},{\\"name\\":\\"RUBBER TYRE GANTRY\\",\\"quantity\\":36},{\\"name\\":\\"Chassis,dolly, translifter\\",\\"quantity\\":9}]',
  ),
);

        foreach ($sites as $siteData) {
            $site = Site::firstOrCreate(
                ['name' => $siteData['site_name']],
                ['region' => 'Pelindo 3']
            );

            $equipments = json_decode($siteData['equipments'], true);
            if ($equipments) {
                foreach ($equipments as $eq) {
                    $eqModel = EquipmentType::where('name', 'LIKE', '%' . $eq['name'] . '%')
                        ->orWhere('code', $eq['name'])->first();
                    
                    if ($eqModel) {
                        $site->equipments()->updateOrCreate(
                            ['equipment_type_id' => $eqModel->id],
                            ['quantity' => $eq['quantity']]
                        );
                    }
                }
            }
        }
    }
}