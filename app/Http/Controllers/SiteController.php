<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Exports\SitesExport;
use App\Imports\SitesImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::with(['equipments.equipmentType'])->get()->map(function ($site) {
            return [
                'id' => $site->id,
                'name' => $site->name,
                'region' => $site->region,
                'work_scheme' => $site->work_scheme ?? 'Non-Shift',
                'jumlah_alat' => $site->equipments->sum('quantity'),
                'site_class' => $site->site_class ?? '-',
                'total_weight' => $site->total_weight ?? 0,
                'technical_staff_needed' => $site->technical_staff_needed,
                'non_technical_staff_needed' => $site->non_technical_staff_needed,
                'existing_technical_staff' => $site->existing_technical_staff,
                'existing_non_technical_staff' => $site->existing_non_technical_staff,
                'equipments' => $site->equipments->map(function ($eq) {
                    return [
                        'id' => $eq->id,
                        'equipment_type_id' => $eq->equipment_type_id,
                        'equipment_type_name' => $eq->equipmentType ? $eq->equipmentType->name : '-',
                        'code' => $eq->equipmentType ? $eq->equipmentType->code : '-',
                        'weight' => $eq->equipmentType ? $eq->equipmentType->weight : 0,
                        'quantity' => $eq->quantity,
                    ];
                }),
            ];
        });

        $equipmentTypes = EquipmentType::all();

        return Inertia::render('Sites/Index', [
            'sites' => $sites,
            'equipmentTypes' => $equipmentTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'work_scheme' => 'required|string|in:Shift,Non-Shift',
            'existing_technical_staff' => 'required|integer|min:0',
            'existing_non_technical_staff' => 'required|integer|min:0',
            'equipments' => 'nullable|array',
            'equipments.*.equipment_type_id' => 'required|exists:equipment_types,id',
            'equipments.*.quantity' => 'required|integer|min:1',
        ], [
            'equipments.*.equipment_type_id.required' => 'Jenis alat wajib dipilih.',
            'equipments.*.quantity.min' => 'Jumlah minimal 1.',
        ]);

        DB::transaction(function () use ($validated) {
            $site = Site::create([
                'name' => $validated['name'],
                'region' => $validated['region'],
                'work_scheme' => $validated['work_scheme'],
                'existing_technical_staff' => $validated['existing_technical_staff'],
                'existing_non_technical_staff' => $validated['existing_non_technical_staff'],
            ]);

            if (!empty($validated['equipments'])) {
                $site->equipments()->createMany($validated['equipments']);
            }
        });
        
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('success', 'Site berhasil ditambahkan.');
    }

    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'work_scheme' => 'required|string|in:Shift,Non-Shift',
            'existing_technical_staff' => 'required|integer|min:0',
            'existing_non_technical_staff' => 'required|integer|min:0',
            'equipments' => 'nullable|array',
            'equipments.*.id' => 'nullable|exists:site_equipments,id',
            'equipments.*.equipment_type_id' => 'required|exists:equipment_types,id',
            'equipments.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $site) {
            $site->update([
                'name' => $validated['name'],
                'region' => $validated['region'],
                'work_scheme' => $validated['work_scheme'],
                'existing_technical_staff' => $validated['existing_technical_staff'],
                'existing_non_technical_staff' => $validated['existing_non_technical_staff'],
            ]);

            $existingIds = [];
            if (!empty($validated['equipments'])) {
                foreach ($validated['equipments'] as $eqData) {
                    if (isset($eqData['id']) && $eqData['id']) {
                        $site->equipments()->where('id', $eqData['id'])->update([
                            'equipment_type_id' => $eqData['equipment_type_id'],
                            'quantity' => $eqData['quantity'],
                        ]);
                        $existingIds[] = $eqData['id'];
                    } else {
                        $newEq = $site->equipments()->create([
                            'equipment_type_id' => $eqData['equipment_type_id'],
                            'quantity' => $eqData['quantity'],
                        ]);
                        $existingIds[] = $newEq->id;
                    }
                }
            }
            
            // Delete removed equipments
            $site->equipments()->whereNotIn('id', $existingIds)->delete();
        });
        
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('success', 'Site berhasil diperbarui.');
    }

    public function destroy(Site $site)
    {
        $site->delete();
        
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();
        
        return redirect()->back()->with('success', 'Site berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new SitesExport, 'sites_and_equipments.xlsx');
    }

    public function downloadTemplate()
    {
        return Excel::download(new SitesExport, 'Template_Import_Sites.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new SitesImport, $request->file('file'));
            resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();
            return redirect()->back()->with('success', 'Data sites dan alat berhasil diimpor.');
        } catch (\Exception $e) {
            // If the exception comes from our SitesImport custom validation, we still want to recalculate
            resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();
            
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
