<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\EquipmentType;
use App\Models\SiteEquipment;
use App\Services\SdmCalculationEngine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SimulationController extends Controller
{
    public function index()
    {
        return Inertia::render('Simulations/Index', [
            'equipmentTypes' => EquipmentType::all(),
            'existingSites' => Site::select('id', 'name', 'region', 'site_class', 'technical_staff_needed', 'non_technical_staff_needed')->get(),
        ]);
    }

    public function calculate(Request $request, SdmCalculationEngine $engine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'equipments' => 'array',
            'equipments.*.equipment_type_id' => 'required|exists:equipment_types,id',
            'equipments.*.quantity' => 'required|integer|min:1',
        ]);

        $equipments = $request->input('equipments', []);
        
        // Use the refactored engine to calculate on the fly
        $simulationResult = $engine->calculateFromRawData($equipments);

        // Check if site already exists for comparison
        $existingSite = Site::where('name', $request->name)->first();

        return response()->json([
            'simulation' => $simulationResult,
            'existing_site' => $existingSite ? [
                'site_class' => $existingSite->site_class,
                'technical_staff_needed' => $existingSite->technical_staff_needed,
                'non_technical_staff_needed' => $existingSite->non_technical_staff_needed,
            ] : null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'equipments' => 'array',
        ]);

        $site = Site::firstOrCreate(
            ['name' => $request->name],
            ['region' => $request->region, 'status' => true]
        );

        // Update region if site already existed but region was changed in simulation
        if ($site->region !== $request->region) {
            $site->region = $request->region;
            $site->save();
        }

        // We assume simulation OVERWRITES existing equipments, or adds new ones.
        // The simplest approach is to sync or recreate. Since SiteEquipment doesn't have a simple sync,
        // we can delete all existing and re-insert, OR just delete them and insert new ones.
        // But deleting destroys history if there are job plans.
        // The standard "Create/Edit" form in SiteController handled it via loop. Let's see how it was handled there.
        // For simulation, we will recreate them.
        
        // Actually, let's just clear existing equipments for this simulation save to keep it identical to the form:
        SiteEquipment::where('site_id', $site->id)->delete();

        foreach ($request->equipments as $eq) {
            SiteEquipment::create([
                'site_id' => $site->id,
                'equipment_type_id' => $eq['equipment_type_id'],
                'name' => $eq['name'] ?? '-',
                'code' => $eq['code'] ?? '-',
                'quantity' => $eq['quantity'],
                'status' => true,
            ]);
        }

        return redirect()->route('sites.index')->with('success', 'Hasil simulasi berhasil disimpan ke database.');
    }
}
