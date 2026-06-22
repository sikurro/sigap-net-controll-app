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

        // 2. Determine Site Class
        $siteClass = $this->determineSiteClass($totalHours);

        // 3. Calculate Technical Staff Needed
        $technicalStaffNeeded = $this->calculateTechnicalStaff($totalHours);

        // 4. Calculate Non-Technical Staff Needed
        $nonTechnicalStaffNeeded = $this->calculateNonTechnicalStaff($siteClass);

        // 5. Update Site Model
        $site->update([
            'total_maintenance_hours' => $totalHours,
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
        $totalHours = 0;

        // Iterate through each equipment at the site
        foreach ($site->equipments as $siteEquipment) {
            $qty = $siteEquipment->quantity;
            if ($qty <= 0 || !$siteEquipment->is_active) {
                continue;
            }

            $equipmentType = $siteEquipment->equipmentType;
            if (!$equipmentType) {
                continue;
            }

            // Sum up hours from all job plans for this equipment type
            $jobPlansHours = 0;
            foreach ($equipmentType->jobPlans as $jobPlan) {
                // job plan duration is in minutes
                $hoursPerOccurrence = $jobPlan->duration_minutes / 60;
                $hoursPerYear = $hoursPerOccurrence * $jobPlan->frequency_per_year;
                $jobPlansHours += $hoursPerYear;
            }

            // Multiply by quantity of equipment
            $totalHours += ($jobPlansHours * $qty);
        }

        return $totalHours;
    }

    /**
     * Determine the class of the site based on thresholds from settings
     */
    protected function determineSiteClass(float $totalHours): ?string
    {
        $thresholds = Setting::getValue('site_class_thresholds', []);

        if (empty($thresholds)) {
            return null; // Fallback if setting is missing
        }

        // We assume the thresholds are sorted by min_hours descending (A first)
        // Let's sort them just to be safe
        usort($thresholds, function ($a, $b) {
            return $b['min_hours'] <=> $a['min_hours'];
        });

        foreach ($thresholds as $threshold) {
            if ($totalHours >= $threshold['min_hours']) {
                return $threshold['class'];
            }
        }

        // If it falls below all, return the lowest class
        return end($thresholds)['class'] ?? null;
    }

    /**
     * Calculate how many technical staff are needed
     */
    protected function calculateTechnicalStaff(float $totalHours): float
    {
        $effectiveWorkingHours = Setting::getValue('effective_working_hours_per_year', 1800);

        if ($effectiveWorkingHours <= 0) {
            return 0;
        }

        // Return exact value (could be decimal like 4.5, which means 4-5 people needed)
        // Or we could use ceil() to round up. Let's return the precise float value, 
        // rounded to 2 decimal places so it looks clean on UI.
        return round($totalHours / $effectiveWorkingHours, 2);
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
