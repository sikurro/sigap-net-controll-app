<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return Inertia::render('Settings/Index', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*.id' => 'required|exists:settings,id',
            'settings.*.value' => 'required',
        ]);

        foreach ($data['settings'] as $item) {
            $setting = Setting::find($item['id']);
            if ($setting) {
                // If it is JSON, we might want to validate it's valid JSON
                if ($setting->type === 'json') {
                    json_decode($item['value']);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return back()->withErrors(['settings' => 'Format JSON tidak valid untuk setting: ' . $setting->key]);
                    }
                }
                $setting->update(['value' => $item['value']]);
            }
        }

        // Trigger engine re-calculation for all sites since settings changed
        $engine = app(\App\Services\SdmCalculationEngine::class);
        $engine->recalculateAllSites();

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
