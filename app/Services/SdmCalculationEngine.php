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
            $rawEquipments[] = [
                'equipment_type_id' => $siteEquipment->equipment_type_id,
                'quantity' => $siteEquipment->quantity,
            ];
        }

        // 5. Calculate Technical Staff Needed
        $technicalStaffNeeded = $this->calculateTechnicalStaff($rawEquipments, $totalHours, $site->work_scheme ?? 'Non-Shift');

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

        return $site;
    }

    /**
     * Calculate total annual maintenance hours for a site
     */
    protected function calculateTotalMaintenanceHours(Site $site): float
    {
        $totalHours = 0;

        foreach ($site->equipments as $siteEquipment) {
            $equipmentType = $siteEquipment->equipmentType;
            if (!$equipmentType) {
                continue;
            }

            foreach ($equipmentType->jobPlans as $jobPlan) {
                $hoursPerOccurrence = $jobPlan->duration_minutes / 60;
                $hoursPerYear = $hoursPerOccurrence * $jobPlan->frequency_per_year;
                $totalHours += $hoursPerYear * $siteEquipment->quantity;
            }
        }

        return round($totalHours, 2);
    }

    /**
     * Calculate total weight for a site
     */
    protected function calculateTotalWeight(Site $site): int
    {
        $totalWeight = 0;

        foreach ($site->equipments as $siteEquipment) {
            $equipmentType = $siteEquipment->equipmentType;
            if ($equipmentType) {
                $totalWeight += $equipmentType->weight * $siteEquipment->quantity;
            }
        }

        return $totalWeight;
    }

    /**
     * Calculate total hours from raw equipment array
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

            foreach ($equipmentType->jobPlans as $jobPlan) {
                $hoursPerOccurrence = $jobPlan->duration_minutes / 60;
                $hoursPerYear = $hoursPerOccurrence * $jobPlan->frequency_per_year;
                $totalHours += $hoursPerYear * $qty;
            }
        }

        return round($totalHours, 2);
    }

    /**
     * Calculate total weight from raw equipment array
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
            if ($equipmentType) {
                $totalWeight += $equipmentType->weight * $qty;
            }
        }

        return $totalWeight;
    }

    /**
     * Calculate all parameters from raw equipment data without saving
     */
    public function calculateFromRawData(array $equipments, string $workScheme = 'Non-Shift'): array
    {
        $totalHours = $this->calculateTotalHoursFromRaw($equipments);
        $totalWeight = $this->calculateTotalWeightFromRaw($equipments);
        $siteClass = $this->determineSiteClass($totalWeight);
        $technicalStaffNeeded = $this->calculateTechnicalStaff($equipments, $totalHours, $workScheme);
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

        if ($siteClasses->isEmpty()) {
            return null;
        }

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
     * Get dynamically calculated productive hours per year based on Man Hours Matrix
     */
    public function getProductiveHours(string $scheme = 'Non-Shift'): float
    {
        $matrixSetting = Setting::getValue('man_hours_matrix');
        if (!$matrixSetting) {
            return 1800; // Fallback
        }

        $matrix = is_string($matrixSetting) ? json_decode($matrixSetting, true) : $matrixSetting;
        $data = ($scheme === 'Shift') ? ($matrix['shift'] ?? null) : ($matrix['non_shift'] ?? null);

        if (!$data) {
            return 1800;
        }

        $hoursPerDay = floatval($data['hours_per_day'] ?? 8);
        $activeDays = floatval($data['active_days_per_year'] ?? 240);

        if (($matrix['calendar_mode'] ?? 'annual') === 'weekly') {
            $daysPerWeek = floatval($data['days_per_week'] ?? 5);
            $weeksPerYear = floatval($data['weeks_per_year'] ?? 48);
            $activeDays = $daysPerWeek * $weeksPerYear;
        }

        $baseAnnualHours = $hoursPerDay * $activeDays;
        $leaveHours = floatval($data['annual_leave_hours'] ?? 0) + floatval($data['sick_leave_hours'] ?? 0);
        $availableHours = max(0, $baseAnnualHours - $leaveHours);

        $deductions = $data['daily_deductions'] ?? [];
        $dailyLeak = floatval($deductions['meal_rest'] ?? 0)
                   + floatval($deductions['meeting_report_travel'] ?? 0)
                   + floatval($deductions['training_doc'] ?? 0)
                   + floatval($deductions['standby'] ?? 0)
                   + floatval($deductions['skill_factor'] ?? 0)
                   + floatval($deductions['other'] ?? 0);

        $annualLeak = $dailyLeak * $activeDays;
        $productiveHours = max(1, $availableHours - $annualLeak);

        return round($productiveHours);
    }

    /**
     * Calculate how many technical staff are needed
     */
    protected function calculateTechnicalStaff(array $rawEquipments, float $totalHours, string $workScheme = 'Non-Shift'): float
    {
        if (empty($rawEquipments)) {
            return 0;
        }

        $effectiveWorkingHours = $this->getProductiveHours($workScheme);

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
