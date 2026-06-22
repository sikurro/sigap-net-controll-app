<?php

namespace App\Http\Controllers;

use App\Models\SiteClass;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SiteClassController extends Controller
{
    public function index()
    {
        $siteClasses = SiteClass::all();
        return Inertia::render('Master/SiteClasses/Index', [
            'siteClasses' => $siteClasses
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:site_classes,name',
            'min_weight' => 'required|integer|min:0',
            'max_weight' => 'nullable|integer|gt:min_weight',
        ]);

        SiteClass::create($validated);
        
        // Trigger recalculation for all sites
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('message', 'Site Class created successfully.');
    }

    public function update(Request $request, SiteClass $siteClass)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:site_classes,name,' . $siteClass->id,
            'min_weight' => 'required|integer|min:0',
            'max_weight' => 'nullable|integer|gt:min_weight',
        ]);

        $siteClass->update($validated);

        // Trigger recalculation for all sites
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('message', 'Site Class updated successfully.');
    }

    public function destroy(SiteClass $siteClass)
    {
        $siteClass->delete();
        return redirect()->back()->with('message', 'Site Class deleted successfully.');
    }
}
