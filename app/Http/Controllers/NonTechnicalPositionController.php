<?php

namespace App\Http\Controllers;

use App\Models\NonTechnicalPosition;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NonTechnicalPositionController extends Controller
{
    public function index()
    {
        $positions = NonTechnicalPosition::all();
        return Inertia::render('Master/NonTechnicalPositions/Index', [
            'positions' => $positions
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:fungsional,non-fungsional',
        ]);

        NonTechnicalPosition::create($validated);
        return redirect()->back()->with('message', 'Position created successfully.');
    }

    public function update(Request $request, NonTechnicalPosition $nonTechnicalPosition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:fungsional,non-fungsional',
        ]);

        $nonTechnicalPosition->update($validated);
        return redirect()->back()->with('message', 'Position updated successfully.');
    }

    public function destroy(NonTechnicalPosition $nonTechnicalPosition)
    {
        $nonTechnicalPosition->delete();
        return redirect()->back()->with('message', 'Position deleted successfully.');
    }
}
