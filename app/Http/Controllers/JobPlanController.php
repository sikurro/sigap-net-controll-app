<?php

namespace App\Http\Controllers;

use App\Models\JobPlan;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobPlanController extends Controller
{
    public function index()
    {
        $jobPlans = JobPlan::with('equipmentType')->get();
        $equipmentTypes = EquipmentType::all();
        
        return Inertia::render('Master/JobPlans/Index', [
            'jobPlans' => $jobPlans,
            'equipmentTypes' => $equipmentTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'activity_name' => 'required|string|max:255',
            'duration_hours' => 'required|numeric|min:0',
            'frequency_per_year' => 'required|integer|min:0',
        ]);
        
        $validated['total_hours_per_year'] = $validated['duration_hours'] * $validated['frequency_per_year'];

        JobPlan::create($validated);
        return redirect()->back()->with('message', 'Job Plan created successfully.');
    }

    public function update(Request $request, JobPlan $jobPlan)
    {
        $validated = $request->validate([
            'equipment_type_id' => 'required|exists:equipment_types,id',
            'activity_name' => 'required|string|max:255',
            'duration_hours' => 'required|numeric|min:0',
            'frequency_per_year' => 'required|integer|min:0',
        ]);

        $validated['total_hours_per_year'] = $validated['duration_hours'] * $validated['frequency_per_year'];

        $jobPlan->update($validated);
        return redirect()->back()->with('message', 'Job Plan updated successfully.');
    }

    public function destroy(JobPlan $jobPlan)
    {
        $jobPlan->delete();
        return redirect()->back()->with('message', 'Job Plan deleted successfully.');
    }
}
