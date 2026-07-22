<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EquipmentTypeController extends Controller
{
    public function index()
    {
        $equipmentTypes = EquipmentType::with(['jobPlans', 'categoryBaseline'])->get();
        $baselines = \App\Models\EquipmentCategoryBaseline::all();
        
        return Inertia::render('Master/EquipmentTypes/Index', [
            'equipmentTypes' => $equipmentTypes,
            'baselines' => $baselines
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:equipment_types,code',
            'weight' => 'required|integer|min:0',
            'category_id' => 'required|exists:equipment_category_baselines,id',
            'job_plans' => 'nullable|array',
            'job_plans.*.activity_name' => 'required|string|max:255',
            'job_plans.*.type' => 'nullable|string|in:DL,MB,TB',
            'job_plans.*.duration_minutes' => 'required|numeric|min:0',
            'job_plans.*.frequency_per_year' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $equipmentType = EquipmentType::create([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
                'weight' => $validated['weight'],
                'category_id' => $validated['category_id'],
            ]);

            if (!empty($validated['job_plans'])) {
                $jobPlans = [];
                foreach ($validated['job_plans'] as $jp) {
                    $durationMin = floatval($jp['duration_minutes']);
                    $frequency = floatval($jp['frequency_per_year']);
                    $jobPlans[] = [
                        'activity_name' => $jp['activity_name'],
                        'type' => !empty($jp['type']) ? strtoupper($jp['type']) : 'MB',
                        'duration_minutes' => $durationMin,
                        'frequency_per_year' => $frequency,
                        'total_hours_per_year' => ($durationMin / 60) * $frequency,
                    ];
                }
                $equipmentType->jobPlans()->createMany($jobPlans);
            }
        });

        // Trigger recalculation for all sites
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('message', 'Equipment Type created successfully.');
    }

    public function update(Request $request, EquipmentType $equipmentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:equipment_types,code,' . $equipmentType->id,
            'weight' => 'required|integer|min:0',
            'category_id' => 'required|exists:equipment_category_baselines,id',
            'job_plans' => 'nullable|array',
            'job_plans.*.activity_name' => 'required|string|max:255',
            'job_plans.*.type' => 'nullable|string|in:DL,MB,TB',
            'job_plans.*.duration_minutes' => 'required|numeric|min:0',
            'job_plans.*.frequency_per_year' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($equipmentType, $validated) {
            $equipmentType->update([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
                'weight' => $validated['weight'],
                'category_id' => $validated['category_id'],
            ]);

            // Sync job plans: Delete-Insert strategy
            $equipmentType->jobPlans()->delete();

            if (!empty($validated['job_plans'])) {
                $jobPlans = [];
                foreach ($validated['job_plans'] as $jp) {
                    $durationMin = floatval($jp['duration_minutes']);
                    $frequency = floatval($jp['frequency_per_year']);
                    $jobPlans[] = [
                        'activity_name' => $jp['activity_name'],
                        'type' => !empty($jp['type']) ? strtoupper($jp['type']) : 'MB',
                        'duration_minutes' => $durationMin,
                        'frequency_per_year' => $frequency,
                        'total_hours_per_year' => ($durationMin / 60) * $frequency,
                    ];
                }
                $equipmentType->jobPlans()->createMany($jobPlans);
            }
        });

        // Trigger recalculation for all sites
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('message', 'Equipment Type updated successfully.');
    }

    public function destroy(EquipmentType $equipmentType)
    {
        $equipmentType->delete();
        return redirect()->back()->with('message', 'Equipment Type deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');

        DB::transaction(function () use ($file) {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $currentEquipmentType = null;
            $parsingState = ''; // 'equipment' or 'jobplan'

            foreach ($rows as $row) {
                if (empty(trim($row[0] ?? ''))) {
                    continue;
                }

                $colA = trim($row[0]);
                $colA_lower = strtolower($colA);

                // Detect headers to switch parsing state
                if ($colA_lower === 'nama jenis alat') {
                    $parsingState = 'equipment';
                    continue;
                }

                if ($colA_lower === 'kegiatan') {
                    $parsingState = 'jobplan';
                    continue;
                }

                if ($parsingState === 'equipment') {
                    $name = $colA;
                    $code = !empty($row[1]) ? trim($row[1]) : '';
                    $weight = !empty($row[2]) ? intval($row[2]) : 0;
                    $categoryName = !empty($row[3]) ? trim($row[3]) : 'Others';

                    if (empty($code)) {
                        $words = explode(' ', $name);
                        foreach ($words as $w) {
                            $code .= substr($w, 0, 1);
                        }
                        if (strlen($name) <= 4) {
                            $code = $name;
                        }
                    }

                    $code = strtoupper($code);
                    
                    $category = \App\Models\EquipmentCategoryBaseline::firstOrCreate(
                        ['category' => $categoryName],
                        ['baseline' => 0]
                    );

                    // If equipment type already exists by name, we find it. Otherwise we find a unique code to create it.
                    $existing = EquipmentType::where('name', $name)->first();
                    if ($existing) {
                        $existing->update([
                            'code' => $code,
                            'category_id' => $category->id,
                            'weight' => $weight
                        ]);
                        $currentEquipmentType = $existing;
                    } else {
                        $originalCode = $code;
                        $counter = 1;
                        while (EquipmentType::where('code', $code)->exists()) {
                            $code = $originalCode . $counter;
                            $counter++;
                        }
                        $currentEquipmentType = EquipmentType::create([
                            'name' => $name,
                            'code' => $code,
                            'category_id' => $category->id,
                            'weight' => $weight
                        ]);
                    }

                    // Reset state so consecutive rows aren't accidentally parsed as equipments
                    $parsingState = ''; 
                    continue;
                }

                if ($parsingState === 'jobplan' && $currentEquipmentType) {
                    $activityName = $colA;
                    $durationMinutes = floatval($row[1] ?? 0);
                    $frequency = floatval($row[2] ?? 0); // Column C is Frekuensi / Tahun
                    $rawType = strtoupper(trim($row[5] ?? ''));
                    $type = in_array($rawType, ['DL', 'MB', 'TB']) ? $rawType : 'MB';

                    if ($durationMinutes > 0) {
                        $totalHours = ($durationMinutes / 60) * $frequency;
                        $currentEquipmentType->jobPlans()->updateOrCreate(
                            ['activity_name' => $activityName],
                            [
                                'type' => $type,
                                'duration_minutes' => $durationMinutes,
                                'frequency_per_year' => $frequency,
                                'total_hours_per_year' => $totalHours,
                            ]
                        );
                    }
                }
            }
        });

        // Trigger recalculation for all sites since weights might have changed
        resolve(\App\Services\SdmCalculationEngine::class)->recalculateAllSites();

        return redirect()->back()->with('message', 'Data Master Alat dan Job Plan berhasil di-import.');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('JOB PLAN');

        $styleBold = ['font' => ['bold' => true]];

        // Block 1
        $sheet->setCellValue('A1', 'Nama Jenis Alat');
        $sheet->setCellValue('B1', 'Kode');
        $sheet->setCellValue('C1', 'Bobot');
        $sheet->setCellValue('D1', 'Kategori');
        $sheet->getStyle('A1:D1')->applyFromArray($styleBold);

        $sheet->setCellValue('A2', 'GRAB SHIP UNLOADER');
        $sheet->setCellValue('B2', 'GSU');
        $sheet->setCellValue('C2', 83);
        $sheet->setCellValue('D2', 'Crane');

        $sheet->setCellValue('A3', 'Kegiatan');
        $sheet->setCellValue('B3', 'Durasi (Menit)');
        $sheet->setCellValue('C3', 'Frekuensi / Tahun');
        $sheet->setCellValue('D3', 'Durasi (Jam)');
        $sheet->setCellValue('E3', 'Jam/tahun');
        $sheet->setCellValue('F3', 'Tipe');
        $sheet->getStyle('A3:F3')->applyFromArray($styleBold);

        $sheet->setCellValue('A4', 'Daily Inspection');
        $sheet->setCellValue('B4', 82.5);
        $sheet->setCellValue('C4', 365);
        $sheet->setCellValue('D4', '=B4/60');
        $sheet->setCellValue('E4', '=C4*D4');
        $sheet->setCellValue('F4', 'DL');

        $sheet->setCellValue('A5', 'Service 250 & 750');
        $sheet->setCellValue('B5', 270);
        $sheet->setCellValue('C5', 36);
        $sheet->setCellValue('D5', '=B5/60');
        $sheet->setCellValue('E5', '=C5*D5');
        $sheet->setCellValue('F5', 'MB');

        // Block 2
        $row = 7;
        $sheet->setCellValue("A{$row}", 'Nama Jenis Alat');
        $sheet->setCellValue("B{$row}", 'Kode');
        $sheet->setCellValue("C{$row}", 'Bobot');
        $sheet->setCellValue("D{$row}", 'Kategori');
        $sheet->getStyle("A{$row}:D{$row}")->applyFromArray($styleBold);

        $row++;
        $sheet->setCellValue("A{$row}", 'REACH STACKER');
        $sheet->setCellValue("B{$row}", 'RS');
        $sheet->setCellValue("C{$row}", 23);
        $sheet->setCellValue("D{$row}", 'Mobile Equipment');

        $row++;
        $sheet->setCellValue("A{$row}", 'Kegiatan');
        $sheet->setCellValue("B{$row}", 'Durasi (Menit)');
        $sheet->setCellValue("C{$row}", 'Frekuensi / Tahun');
        $sheet->setCellValue("D{$row}", 'Durasi (Jam)');
        $sheet->setCellValue("E{$row}", 'Jam/tahun');
        $sheet->setCellValue("F{$row}", 'Tipe');
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($styleBold);

        $row++;
        $sheet->setCellValue("A{$row}", 'Daily Inspection');
        $sheet->setCellValue("B{$row}", 82.5);
        $sheet->setCellValue("C{$row}", 365);
        $sheet->setCellValue("D{$row}", '=B'.$row.'/60');
        $sheet->setCellValue("E{$row}", '=C'.$row.'*D'.$row);
        $sheet->setCellValue("F{$row}", 'DL');

        $row++;
        $sheet->setCellValue("A{$row}", 'Service 250 & 750');
        $sheet->setCellValue("B{$row}", 270);
        $sheet->setCellValue("C{$row}", 36);
        $sheet->setCellValue("D{$row}", '=B'.$row.'/60');
        $sheet->setCellValue("E{$row}", '=C'.$row.'*D'.$row);
        $sheet->setCellValue("F{$row}", 'MB');

        $row++;
        $sheet->setCellValue("A{$row}", 'Service 500');
        $sheet->setCellValue("B{$row}", 405);
        $sheet->setCellValue("C{$row}", 18);
        $sheet->setCellValue("D{$row}", '=B'.$row.'/60');
        $sheet->setCellValue("E{$row}", '=C'.$row.'*D'.$row);
        $sheet->setCellValue("F{$row}", 'TB');

        // Auto size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'template_import_alat.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}


