<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::with(['equipments.equipmentType'])->get()->map(function ($site) {
            return [
                'id' => $site->id,
                'name' => $site->name,
                'region' => $site->region,
                'status' => $site->status,
                'jumlah_alat' => $site->equipments->sum('quantity'),
                'standar_teknisi' => $site->required_technicians,
                'standar_non_teknisi' => $site->required_non_technicians,
                'equipments' => $site->equipments->map(function ($eq) {
                    return [
                        'id' => $eq->id,
                        'name' => $eq->name,
                        'code' => $eq->code,
                        'equipment_type_id' => $eq->equipment_type_id,
                        'equipment_type_name' => $eq->equipmentType ? $eq->equipmentType->name : '-',
                        'status' => $eq->status,
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
            'status' => 'required|boolean',
            'equipments' => 'nullable|array',
            'equipments.*.name' => 'required|string|max:255',
            'equipments.*.code' => 'required|string|max:255',
            'equipments.*.equipment_type_id' => 'required|exists:equipment_types,id',
            'equipments.*.status' => 'required|boolean',
            'equipments.*.quantity' => 'required|integer|min:1',
        ], [
            'equipments.*.name.required' => 'Nama alat wajib diisi.',
            'equipments.*.code.required' => 'Kode alat wajib diisi.',
            'equipments.*.equipment_type_id.required' => 'Jenis alat wajib dipilih.',
            'equipments.*.quantity.min' => 'Jumlah minimal 1.',
        ]);

        DB::transaction(function () use ($validated) {
            $site = Site::create([
                'name' => $validated['name'],
                'region' => $validated['region'],
                'status' => $validated['status'],
            ]);

            if (!empty($validated['equipments'])) {
                $site->equipments()->createMany($validated['equipments']);
            }
        });

        return redirect()->back()->with('success', 'Site berhasil ditambahkan.');
    }

    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'status' => 'required|boolean',
            'equipments' => 'nullable|array',
            'equipments.*.id' => 'nullable|exists:site_equipments,id',
            'equipments.*.name' => 'required|string|max:255',
            'equipments.*.code' => 'required|string|max:255',
            'equipments.*.equipment_type_id' => 'required|exists:equipment_types,id',
            'equipments.*.status' => 'required|boolean',
            'equipments.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $site) {
            $site->update([
                'name' => $validated['name'],
                'region' => $validated['region'],
                'status' => $validated['status'],
            ]);

            $existingIds = [];
            if (!empty($validated['equipments'])) {
                foreach ($validated['equipments'] as $eqData) {
                    if (isset($eqData['id']) && $eqData['id']) {
                        $site->equipments()->where('id', $eqData['id'])->update([
                            'name' => $eqData['name'],
                            'code' => $eqData['code'],
                            'equipment_type_id' => $eqData['equipment_type_id'],
                            'status' => $eqData['status'],
                            'quantity' => $eqData['quantity'],
                        ]);
                        $existingIds[] = $eqData['id'];
                    } else {
                        $newEq = $site->equipments()->create([
                            'name' => $eqData['name'],
                            'code' => $eqData['code'],
                            'equipment_type_id' => $eqData['equipment_type_id'],
                            'status' => $eqData['status'],
                            'quantity' => $eqData['quantity'],
                        ]);
                        $existingIds[] = $newEq->id;
                    }
                }
            }
            
            // Delete removed equipments
            $site->equipments()->whereNotIn('id', $existingIds)->delete();
        });

        return redirect()->back()->with('success', 'Site berhasil diperbarui.');
    }

    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->back()->with('success', 'Site berhasil dihapus.');
    }
}
