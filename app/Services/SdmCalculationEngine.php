<?php

namespace App\Services;

use App\Models\Site;
use App\Models\Setting;
use App\Models\NonTechnicalRequirement;

class SdmCalculationEngine
{
    /**
     * Calculate and update all SDM parameters for a specific Site.
     *
     * @param Site $site
     * @return void
     */
    public function calculateForSite(Site $site)
    {
        // 1. Calculate Total Maintenance Hours
        $totalHours = $this->calculateTotalMaintenanceHours($site);

        // 2. Calculate Total Weight
        $totalWeight = $this->calculateTotalWeight($site);

        // 3. Determine Site Class
        $siteClass = $this->determineSiteClass($totalWeight);

        // 4. Extract active raw equipments for technical staff calculation
        $rawEquipments = [];
        foreach ($site->equipments as $siteEquipment) {
            if ($siteEquipment->status) {
                $rawEquipments[] = [
                    'equipment_type_id' => $siteEquipment->equipment_type_id,
                    'quantity' => $siteEquipment->quantity,
                ];
            }
        }

        // 5. Calculate Technical Staff Needed
        $technicalStaffNeeded = $this->calculateTechnicalStaff($rawEquipments, $totalHours);

        // 6. Calculate Non-Technical Staff Needed
        $nonTechnicalStaffNeeded = $this->calculateNonTechnicalStaff($siteClass);

        // 7. Update Site Model
        $site->update([
            'total_maintenance_hours' => $totalHours,
            'total_weight' => $totalWeight,
            'site_class' => $siteClass,
            'technical_staff_needed' => $technicalStaffNeeded,
            'non_technical_staff_needed' => $nonTechnicalStaffNeeded,
        ]);
    }

    /**
     * Calculate Total Maintenance Hours for a Site
     */
    protected function calculateTotalMaintenanceHours(Site $site): float
    {
        $rawEquipments = [];
        foreach ($site->equipments as $siteEquipment) {
            if ($siteEquipment->status) {
                $rawEquipments[] = [
                    'equipment_type_id' => $siteEquipment->equipment_type_id,
                    'quantity' => $siteEquipment->quantity,
                ];
            }
        }

        return $this->calculateTotalHoursFromRaw($rawEquipments);
    }

    /**
     * Calculate Total Weight for a Site
     */
    protected function calculateTotalWeight(Site $site): int
    {
        $rawEquipments = [];
        foreach ($site->equipments as $siteEquipment) {
            if ($siteEquipment->status) {
                $rawEquipments[] = [
                    'equipment_type_id' => $siteEquipment->equipment_type_id,
                    'quantity' => $siteEquipment->quantity,
                ];
            }
        }

        return $this->calculateTotalWeightFromRaw($rawEquipments);
    }

    /**
     * Calculate Total Maintenance Hours from raw array of equipments
     * 
     * @param array $equipments Array of ['equipment_type_id' => x, 'quantity' => y]
     */
    protected function calculateTotalHoursFromRaw(array $equipments): float
    {
        $totalHours = 0;

        foreach ($equipments as $eq) {
            $qty = $eq['quantity'] ?? 0;
            if ($qty <= 0) {
                continue;
            }

            $equipmentType = \App\Models\EquipmentType::with('jobPlans')->find($eq['equipment_type_id']);
            if (!$equipmentType) {
                continue;
            }

            $jobPlansHours = 0;
            foreach ($equipmentType->jobPlans as $jobPlan) {
                $hoursPerOccurrence = $jobPlan->duration_minutes / 60;
                $hoursPerYear = $hoursPerOccurrence * $jobPlan->frequency_per_year;
                $jobPlansHours += $hoursPerYear;
            }

            $totalHours += ($jobPlansHours * $qty);
        }

        return $totalHours;
    }

    /**
     * Calculate Total Weight from raw array of equipments
     * 
     * @param array $equipments Array of ['equipment_type_id' => x, 'quantity' => y]
     */
    protected function calculateTotalWeightFromRaw(array $equipments): int
    {
        $totalWeight = 0;

        foreach ($equipments as $eq) {
            $qty = $eq['quantity'] ?? 0;
            if ($qty <= 0) {
                continue;
            }

            $equipmentType = \App\Models\EquipmentType::find($eq['equipment_type_id']);
            if (!$equipmentType) {
                continue;
            }

            $totalWeight += ($equipmentType->weight * $qty);
        }

        return $totalWeight;
    }

    /**
     * Calculate all parameters from raw equipment data without saving
     */
    public function calculateFromRawData(array $equipments): array
    {
        $totalHours = $this->calculateTotalHoursFromRaw($equipments);
        $totalWeight = $this->calculateTotalWeightFromRaw($equipments);
        $siteClass = $this->determineSiteClass($totalWeight);
        $technicalStaffNeeded = $this->calculateTechnicalStaff($equipments, $totalHours);
        $nonTechnicalStaffNeeded = $this->calculateNonTechnicalStaff($siteClass);

        return [
            'total_maintenance_hours' => $totalHours,
            'total_weight' => $totalWeight,
            'site_class' => $siteClass ?? '-',
            'technical_staff_needed' => $technicalStaffNeeded,
            'non_technical_staff_needed' => $nonTechnicalStaffNeeded,
        ];
    }

    /**
     * Determine the class of the site based on weight ranges
     */
    protected function determineSiteClass(int $totalWeight): ?string
    {
        $siteClasses = \App\Models\SiteClass::orderBy('min_weight', 'desc')->get();

        foreach ($siteClasses as $siteClass) {
            if ($totalWeight >= $siteClass->min_weight) {
                if (is_null($siteClass->max_weight) || $totalWeight <= $siteClass->max_weight) {
                    return $siteClass->name;
                }
            }
        }

        // Return E or lowest if not matched
        return $siteClasses->last()->name ?? null;
    }

    /**
     * Calculate how many technical staff are needed
     */
    protected function calculateTechnicalStaff(array $rawEquipments, float $totalHours): float
    {
        if (empty($rawEquipments)) {
            return 0;
        }

        $effectiveWorkingHours = Setting::getValue('effective_working_hours_per_year', 1800);

        if ($effectiveWorkingHours <= 0) {
            return 0;
        }

        $highestBaseline = 0;
        $highestWeight = -1;
        $highestWeightSingleUnitHours = 0;

        foreach ($rawEquipments as $eq) {
            $qty = $eq['quantity'] ?? 0;
            if ($qty <= 0) {
                continue;
            }

            $equipmentType = \App\Models\EquipmentType::with(['jobPlans', 'categoryBaseline'])->find($eq['equipment_type_id']);
            if (!$equipmentType) {
                continue;
            }

            // Find baseline value for this equipment's category
            $baselineVal = $equipmentType->categoryBaseline ? $equipmentType->categoryBaseline->baseline : 0;
            
            if ($baselineVal > $highestBaseline) {
                $highestBaseline = $baselineVal;
            }

            // Calculate annual maintenance hours for this equipment type (1 unit)
            $jobPlansHours = 0;
            foreach ($equipmentType->jobPlans as $jobPlan) {
                $hoursPerOccurrence = $jobPlan->duration_minutes / 60;
                $hoursPerYear = $hoursPerOccurrence * $jobPlan->frequency_per_year;
                $jobPlansHours += $hoursPerYear;
            }

            // Find equipment type with highest weight
            if ($equipmentType->weight > $highestWeight) {
                $highestWeight = $equipmentType->weight;
                $highestWeightSingleUnitHours = $jobPlansHours;
            } elseif ($equipmentType->weight === $highestWeight) {
                if ($jobPlansHours > $highestWeightSingleUnitHours) {
                    $highestWeightSingleUnitHours = $jobPlansHours;
                }
            }
        }

        $additionalHours = max(0, $totalHours - $highestWeightSingleUnitHours);
        $additionalTech = $additionalHours / $effectiveWorkingHours;

        return round($highestBaseline + $additionalTech, 2);
    }

    /**
     * Calculate how many non-technical staff are needed for a given site class
     */
    protected function calculateNonTechnicalStaff(?string $siteClass): int
    {
        if (!$siteClass) {
            return 0;
        }

        $siteClassModel = \App\Models\SiteClass::where('name', $siteClass)->first();
        if (!$siteClassModel) {
            return 0;
        }

        // Sum the quantity for the matching site_class_id
        return NonTechnicalRequirement::where('site_class_id', $siteClassModel->id)
            ->sum('quantity');
    }

    /**
     * Recalculate for all sites (useful when global settings change)
     */
    public function recalculateAllSites()
    {
        $sites = Site::all();
        foreach ($sites as $site) {
            $this->calculateForSite($site);
        }
    }
}
