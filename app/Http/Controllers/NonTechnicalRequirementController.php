<?php

namespace App\Http\Controllers;

use App\Models\SiteClass;
use App\Models\NonTechnicalPosition;
use App\Models\NonTechnicalRequirement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NonTechnicalRequirementController extends Controller
{
    public function index()
    {
        $siteClasses = SiteClass::orderBy('min_weight', 'desc')->get();
        $positions = NonTechnicalPosition::all();
        $requirements = NonTechnicalRequirement::all();

        return Inertia::render('Master/NonTechnicalRequirements/Index', [
            'siteClasses' => $siteClasses,
            'positions' => $positions,
            'requirements' => $requirements,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'requirements' => 'required|array',
            'requirements.*.site_class_id' => 'required|exists:site_classes,id',
            'requirements.*.non_technical_position_id' => 'required|exists:non_technical_positions,id',
            'requirements.*.quantity' => 'required|integer|min:0',
        ]);

        foreach ($validated['requirements'] as $req) {
            NonTechnicalRequirement::updateOrCreate(
                [
                    'site_class_id' => $req['site_class_id'],
                    'non_technical_position_id' => $req['non_technical_position_id']
                ],
                ['quantity' => $req['quantity']]
            );
        }

        return redirect()->back()->with('message', 'Requirements updated successfully.');
    }
}
