<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategoryBaseline;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipmentCategoryBaselineController extends Controller
{
    public function index()
    {
        $baselines = EquipmentCategoryBaseline::all();
        return Inertia::render('Master/EquipmentCategoryBaselines/Index', [
            'baselines' => $baselines,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255|unique:equipment_category_baselines,category',
            'baseline' => 'required|integer|min:0',
        ]);

        EquipmentCategoryBaseline::create($validated);

        // Recalculate SDM for all sites
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('success', 'Baseline kategori berhasil ditambahkan.');
    }

    public function update(Request $request, EquipmentCategoryBaseline $equipmentCategoryBaseline)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255|unique:equipment_category_baselines,category,' . $equipmentCategoryBaseline->id,
            'baseline' => 'required|integer|min:0',
        ]);

        $equipmentCategoryBaseline->update($validated);

        // Recalculate SDM for all sites
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('success', 'Baseline kategori berhasil diperbarui.');
    }

    public function destroy(EquipmentCategoryBaseline $equipmentCategoryBaseline)
    {
        $equipmentCategoryBaseline->delete();

        // Recalculate SDM for all sites
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('success', 'Baseline kategori berhasil dihapus.');
    }
}
