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
            'job_plans' => 'nullable|array',
            'job_plans.*.activity_name' => 'required|string|max:255',
            'job_plans.*.duration_minutes' => 'required|numeric|min:0',
            'job_plans.*.frequency_per_year' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $equipmentType = EquipmentType::create([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
            ]);

            if (!empty($validated['job_plans'])) {
                $jobPlans = [];
                foreach ($validated['job_plans'] as $jp) {
                    $durationMin = floatval($jp['duration_minutes']);
                    $frequency = floatval($jp['frequency_per_year']);
                    $jobPlans[] = [
                        'activity_name' => $jp['activity_name'],
                        'duration_minutes' => $durationMin,
                        'frequency_per_year' => $frequency,
                        'total_hours_per_year' => ($durationMin / 60) * $frequency,
                    ];
                }
                $equipmentType->jobPlans()->createMany($jobPlans);
            }
        });

        return redirect()->back()->with('message', 'Equipment Type created successfully.');
    }

    public function update(Request $request, EquipmentType $equipmentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:equipment_types,code,' . $equipmentType->id,
            'job_plans' => 'nullable|array',
            'job_plans.*.activity_name' => 'required|string|max:255',
            'job_plans.*.duration_minutes' => 'required|numeric|min:0',
            'job_plans.*.frequency_per_year' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($equipmentType, $validated) {
            $equipmentType->update([
                'name' => $validated['name'],
                'code' => $validated['code'] ?? null,
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
                        'duration_minutes' => $durationMin,
                        'frequency_per_year' => $frequency,
                        'total_hours_per_year' => ($durationMin / 60) * $frequency,
                    ];
                }
                $equipmentType->jobPlans()->createMany($jobPlans);
            }
        });

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

            foreach ($rows as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $colA = trim($row[0]);

                // Check if it is an equipment type row (starts with '(')
                if (strpos($colA, '(') === 0) {
                    $name = trim($colA, "() \t\n\r\0\x0B");

                    // Generate code: check if provided in column B (index 1)
                    $code = !empty($row[1]) ? trim($row[1]) : '';

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

                    // If equipment type already exists by name, we find it. Otherwise we find a unique code to create it.
                    $existing = EquipmentType::where('name', $name)->first();
                    if ($existing) {
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
                            'code' => $code
                        ]);
                    }

                    // Sync: delete existing job plans first
                    $currentEquipmentType->jobPlans()->delete();
                    continue;
                }

                // Skip header row
                if (strtolower($colA) === 'kegiatan') {
                    continue;
                }

                // Detail job plan row
                if ($currentEquipmentType) {
                    $activityName = $colA;
                    $durationMinutes = floatval($row[1] ?? 0);
                    $frequency = floatval($row[3] ?? 0); // Column D is Satuan/kali (frequency)

                    if ($durationMinutes > 0) {
                        $totalHours = ($durationMinutes / 60) * $frequency;
                        $currentEquipmentType->jobPlans()->create([
                            'activity_name' => $activityName,
                            'duration_minutes' => $durationMinutes,
                            'frequency_per_year' => $frequency,
                            'total_hours_per_year' => $totalHours,
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('message', 'Data berhasil di-import dari Excel.');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('JOB PLAN');

        // Set headers and sample structure
        $sheet->setCellValue('A1', '(REACH STACKER)');
        $sheet->setCellValue('B1', 'RS');
        
        $sheet->setCellValue('A2', 'Kegiatan');
        $sheet->setCellValue('B2', 'Durasi (Menit)');
        $sheet->setCellValue('C2', 'Durasi (Jam)');
        $sheet->setCellValue('D2', 'Frekuensi / Tahun');
        $sheet->setCellValue('E2', 'Total Jam / Tahun');

        // Sample Row 1
        $sheet->setCellValue('A3', 'Daily Inspection');
        $sheet->setCellValue('B3', 82.5);
        $sheet->setCellValue('C3', '=B3/60');
        $sheet->setCellValue('D3', 365);
        $sheet->setCellValue('E3', '=C3*D3');

        // Sample Row 2
        $sheet->setCellValue('A4', 'Service 250 & 750');
        $sheet->setCellValue('B4', 270);
        $sheet->setCellValue('C4', '=B4/60');
        $sheet->setCellValue('D4', 36);
        $sheet->setCellValue('E4', '=C4*D4');

        // Add second equipment sample
        $sheet->setCellValue('A6', '(FORKLIFT)');
        $sheet->setCellValue('B6', 'FL');
        
        $sheet->setCellValue('A7', 'Kegiatan');
        $sheet->setCellValue('B7', 'Durasi (Menit)');
        $sheet->setCellValue('C7', 'Durasi (Jam)');
        $sheet->setCellValue('D7', 'Frekuensi / Tahun');
        $sheet->setCellValue('E7', 'Total Jam / Tahun');

        $sheet->setCellValue('A8', 'Daily Check');
        $sheet->setCellValue('B8', 30);
        $sheet->setCellValue('C8', '=B8/60');
        $sheet->setCellValue('D8', 300);
        $sheet->setCellValue('E8', '=C8*D8');

        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'template_import_alat.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}


