<?php

namespace App\Observers;

use App\Models\JobPlan;
use App\Models\Site;
use App\Services\SdmCalculationEngine;

class JobPlanObserver
{
    protected $engine;

    public function __construct(SdmCalculationEngine $engine)
    {
        $this->engine = $engine;
    }

    protected function recalculateAffectedSites(JobPlan $jobPlan)
    {
        // Find all sites that have the equipment type of this job plan
        if ($jobPlan->equipmentType) {
            $sites = Site::whereHas('equipments', function($q) use ($jobPlan) {
                $q->where('equipment_type_id', $jobPlan->equipment_type_id);
            })->get();

            foreach ($sites as $site) {
                $this->engine->calculateForSite($site);
            }
        }
    }

    public function created(JobPlan $jobPlan): void
    {
        $this->recalculateAffectedSites($jobPlan);
    }

    public function updated(JobPlan $jobPlan): void
    {
        $this->recalculateAffectedSites($jobPlan);
    }

    public function deleted(JobPlan $jobPlan): void
    {
        $this->recalculateAffectedSites($jobPlan);
    }

    public function restored(JobPlan $jobPlan): void
    {
        $this->recalculateAffectedSites($jobPlan);
    }

    public function forceDeleted(JobPlan $jobPlan): void
    {
        $this->recalculateAffectedSites($jobPlan);
    }
}
