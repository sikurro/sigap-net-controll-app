<?php

namespace App\Observers;

use App\Models\SiteEquipment;
use App\Services\SdmCalculationEngine;

class SiteEquipmentObserver
{
    protected $engine;

    public function __construct(SdmCalculationEngine $engine)
    {
        $this->engine = $engine;
    }

    protected function recalculate(SiteEquipment $siteEquipment)
    {
        if ($siteEquipment->site) {
            $this->engine->calculateForSite($siteEquipment->site);
        }
    }

    public function created(SiteEquipment $siteEquipment): void
    {
        $this->recalculate($siteEquipment);
    }

    public function updated(SiteEquipment $siteEquipment): void
    {
        $this->recalculate($siteEquipment);
    }

    public function deleted(SiteEquipment $siteEquipment): void
    {
        $this->recalculate($siteEquipment);
    }

    public function restored(SiteEquipment $siteEquipment): void
    {
        $this->recalculate($siteEquipment);
    }

    public function forceDeleted(SiteEquipment $siteEquipment): void
    {
        $this->recalculate($siteEquipment);
    }
}
