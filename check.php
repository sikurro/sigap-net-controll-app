<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$eq = App\Models\EquipmentType::with('jobPlans')->find(141);
if (!$eq) {
    echo "Equipment not found";
    exit;
}

echo "Equipment: {$eq->name} (ID: {$eq->id})\n";
foreach ($eq->jobPlans as $jp) {
    echo "JobPlan ID: {$jp->id}, Type: {$jp->type}, Interval: {$jp->interval_meter}, Freq: {$jp->frequency_per_year}, Mins: {$jp->duration_minutes}\n";
}

$siteEqs = App\Models\SiteEquipment::where('equipment_type_id', 141)->get();
foreach ($siteEqs as $se) {
    echo "Site ID: {$se->site_id}, Qty: {$se->quantity}, Utilization Rate: {$se->utilization_rate}\n";
}
