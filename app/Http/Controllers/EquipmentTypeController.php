<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use Illuminate\Http\Request;
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
        ]);

        EquipmentType::create($validated);
        return redirect()->back()->with('message', 'Equipment Type created successfully.');
    }

    public function update(Request $request, EquipmentType $equipmentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:equipment_types,code,' . $equipmentType->id,
        ]);

        $equipmentType->update($validated);
        return redirect()->back()->with('message', 'Equipment Type updated successfully.');
    }

    public function destroy(EquipmentType $equipmentType)
    {
        $equipmentType->delete();
        return redirect()->back()->with('message', 'Equipment Type deleted successfully.');
    }
}
