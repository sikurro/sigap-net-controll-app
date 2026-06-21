<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EquipmentTypeController extends Controller
{
    public function index()
    {
        $equipmentTypes = EquipmentType::with('jobPlans')->get();
        return Inertia::render('Master/EquipmentTypes/Index', [
            'equipmentTypes' => $equipmentTypes
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:equipment_types,code',
            'job_plans' => 'nullable|array',
            'job_plans.*.activity_name' => 'required|string|max:255',
            'job_plans.*.duration_hours' => 'required|numeric|min:0',
            'job_plans.*.frequency_per_year' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $equipmentType = EquipmentType::create([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
            ]);

            if (!empty($validated['job_plans'])) {
                $jobPlans = [];
                foreach ($validated['job_plans'] as $jp) {
                    $duration = floatval($jp['duration_hours']);
                    $frequency = floatval($jp['frequency_per_year']);
                    $jobPlans[] = [
                        'activity_name' => $jp['activity_name'],
                        'duration_hours' => $duration,
                        'frequency_per_year' => $frequency,
                        'total_hours_per_year' => $duration * $frequency,
                    ];
                }
                $equipmentType->jobPlans()->createMany($jobPlans);
            }
        });

        return redirect()->back()->with('message', 'Equipment Type created successfully.');
    }

    public function update(Request $request, EquipmentType $equipmentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:equipment_types,code,' . $equipmentType->id,
            'job_plans' => 'nullable|array',
            'job_plans.*.activity_name' => 'required|string|max:255',
            'job_plans.*.duration_hours' => 'required|numeric|min:0',
            'job_plans.*.frequency_per_year' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($equipmentType, $validated) {
            $equipmentType->update([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
            ]);

            // Sync job plans: Delete-Insert strategy
            $equipmentType->jobPlans()->delete();

            if (!empty($validated['job_plans'])) {
                $jobPlans = [];
                foreach ($validated['job_plans'] as $jp) {
                    $duration = floatval($jp['duration_hours']);
                    $frequency = floatval($jp['frequency_per_year']);
                    $jobPlans[] = [
                        'activity_name' => $jp['activity_name'],
                        'duration_hours' => $duration,
                        'frequency_per_year' => $frequency,
                        'total_hours_per_year' => $duration * $frequency,
                    ];
                }
                $equipmentType->jobPlans()->createMany($jobPlans);
            }
        });

        return redirect()->back()->with('message', 'Equipment Type updated successfully.');
    }

    public function destroy(EquipmentType $equipmentType)
    {
        $equipmentType->delete();
        return redirect()->back()->with('message', 'Equipment Type deleted successfully.');
    }
}

