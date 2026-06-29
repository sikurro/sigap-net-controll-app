<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Services\SdmCalculationEngine;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request, SdmCalculationEngine $engine)
    {
        $regionFilter = $request->query('region', 'ALL');

        // Get distinct regions for filter dropdown
        $regions = Site::distinct()->pluck('region')->filter()->values();

        // Build sites query
        $query = Site::with('equipments.equipmentType');
        if ($regionFilter !== 'ALL' && !empty($regionFilter)) {
            $query->where('region', $regionFilter);
        }

        $sites = $query->get();

        // Summary KPI
        $totalSites = $sites->count();
        $sitesByClass = [
            'Utama' => $sites->where('site_class', 'Utama')->count(),
            'Kelas A' => $sites->where('site_class', 'Kelas A')->count(),
            'Kelas B' => $sites->where('site_class', 'Kelas B')->count(),
            'Kelas C' => $sites->where('site_class', 'Kelas C')->count(),
            'Kelas D' => $sites->where('site_class', 'Kelas D')->count(),
            'Kelas E' => $sites->where('site_class', 'Kelas E')->count(),
        ];

        $totalEquipments = 0;
        $techNeeded = 0;
        $techExisting = 0;
        $nonTechNeeded = 0;
        $nonTechExisting = 0;

        $chartComparisonLabels = [];
        $chartComparisonEquip = [];
        $chartComparisonTechNeeded = [];
        $chartComparisonTechExisting = [];
        $chartComparisonNonTechNeeded = [];
        $chartComparisonNonTechExisting = [];

        $chartMaintenanceLabels = [];
        $chartPreventiveHours = [];
        $chartBreakdownHours = [];

        $sitesTable = [];

        foreach ($sites as $site) {
            $equipCount = $site->equipments->sum('quantity');
            $totalEquipments += $equipCount;

            $tNeed = floatval($site->technical_staff_needed);
            $tExist = intval($site->existing_technical_staff);
            $ntNeed = intval($site->non_technical_staff_needed);
            $ntExist = intval($site->existing_non_technical_staff);

            $techNeeded += $tNeed;
            $techExisting += $tExist;
            $nonTechNeeded += $ntNeed;
            $nonTechExisting += $ntExist;

            // Chart data
            $chartComparisonLabels[] = $site->name;
            $chartComparisonEquip[] = $equipCount;
            $chartComparisonTechNeeded[] = $tNeed;
            $chartComparisonTechExisting[] = $tExist;
            $chartComparisonNonTechNeeded[] = $ntNeed;
            $chartComparisonNonTechExisting[] = $ntExist;

            // Calculate maintenance breakdown for stacked chart
            $rawEquipments = [];
            foreach ($site->equipments as $se) {
                $rawEquipments[] = [
                    'equipment_type_id' => $se->equipment_type_id,
                    'quantity' => $se->quantity,
                ];
            }
            $breakdown = $engine->getCalculationBreakdown($rawEquipments, floatval($site->total_maintenance_hours), $site->work_scheme ?? 'Non-Shift', $site->site_class);

            $chartMaintenanceLabels[] = $site->name;
            $chartPreventiveHours[] = $breakdown['total_maintenance_hours'] ?? 0;
            $chartBreakdownHours[] = $breakdown['total_breakdown_hours'] ?? 0;

            // Health badge
            $totalNeedSite = $tNeed + $ntNeed;
            $totalExistSite = $tExist + $ntExist;
            
            $healthStatus = 'Seimbang';
            $healthColor = 'green';
            if ($totalNeedSite > 0) {
                if ($totalExistSite < $totalNeedSite * 0.9) {
                    $healthStatus = 'Under-staffed';
                    $healthColor = 'red';
                } elseif ($totalExistSite > $totalNeedSite * 1.1) {
                    $healthStatus = 'Over-staffed';
                    $healthColor = 'yellow';
                }
            } elseif ($totalExistSite > 0) {
                $healthStatus = 'Over-staffed';
                $healthColor = 'yellow';
            }

            $sitesTable[] = [
                'id' => $site->id,
                'name' => $site->name,
                'region' => $site->region,
                'site_class' => $site->site_class ?? '-',
                'equip_count' => $equipCount,
                'tech_needed' => $tNeed,
                'tech_existing' => $tExist,
                'non_tech_needed' => $ntNeed,
                'non_tech_existing' => $ntExist,
                'health_status' => $healthStatus,
                'health_color' => $healthColor,
            ];
        }

        return Inertia::render('Dashboard', [
            'regionFilter' => $regionFilter,
            'regions' => $regions,
            'summary' => [
                'totalSites' => $totalSites,
                'sitesByClass' => $sitesByClass,
                'totalEquipments' => $totalEquipments,
                'techNeeded' => round($techNeeded, 2),
                'techExisting' => $techExisting,
                'nonTechNeeded' => $nonTechNeeded,
                'nonTechExisting' => $nonTechExisting,
            ],
            'chartData' => [
                'comparison' => [
                    'labels' => $chartComparisonLabels,
                    'series' => [
                        ['name' => 'Jumlah Alat', 'data' => $chartComparisonEquip],
                        ['name' => 'Kebutuhan Teknisi', 'data' => $chartComparisonTechNeeded],
                        ['name' => 'Eksisting Teknisi', 'data' => $chartComparisonTechExisting],
                        ['name' => 'Kebutuhan Non-Teknis', 'data' => $chartComparisonNonTechNeeded],
                        ['name' => 'Eksisting Non-Teknis', 'data' => $chartComparisonNonTechExisting],
                    ]
                ],
                'composition' => [
                    'labels' => ['Tenaga Teknis Lapangan', 'Staf Non-Teknis'],
                    'series' => [round($techNeeded, 2), floatval($nonTechNeeded)]
                ],
                'maintenance' => [
                    'labels' => $chartMaintenanceLabels,
                    'series' => [
                        ['name' => 'Jam Rutin (Preventive)', 'data' => $chartPreventiveHours],
                        ['name' => 'Jam Cadangan (Breakdown Allowance)', 'data' => $chartBreakdownHours],
                    ]
                ]
            ],
            'sitesTable' => $sitesTable,
        ]);
    }

    public function printReport(Request $request, SdmCalculationEngine $engine)
    {
        $regionFilter = $request->query('region', 'ALL');

        $query = Site::with('equipments.equipmentType');
        if ($regionFilter !== 'ALL' && !empty($regionFilter)) {
            $query->where('region', $regionFilter);
        }

        $sites = $query->get();
        $detailedSites = [];

        $totalTechNeeded = 0;
        $totalTechExisting = 0;
        $totalNonTechNeeded = 0;
        $totalNonTechExisting = 0;

        foreach ($sites as $site) {
            $rawEquipments = [];
            foreach ($site->equipments as $se) {
                $rawEquipments[] = [
                    'equipment_type_id' => $se->equipment_type_id,
                    'quantity' => $se->quantity,
                ];
            }
            $breakdown = $engine->getCalculationBreakdown($rawEquipments, floatval($site->total_maintenance_hours), $site->work_scheme ?? 'Non-Shift', $site->site_class);

            $tNeed = floatval($site->technical_staff_needed);
            $tExist = intval($site->existing_technical_staff);
            $ntNeed = intval($site->non_technical_staff_needed);
            $ntExist = intval($site->existing_non_technical_staff);

            $totalTechNeeded += $tNeed;
            $totalTechExisting += $tExist;
            $totalNonTechNeeded += $ntNeed;
            $totalNonTechExisting += $ntExist;

            $detailedSites[] = [
                'site' => $site,
                'breakdown' => $breakdown,
                'tech_needed' => $tNeed,
                'tech_existing' => $tExist,
                'non_tech_needed' => $ntNeed,
                'non_tech_existing' => $ntExist,
            ];
        }

        return Inertia::render('Reports/PrintReport', [
            'regionFilter' => $regionFilter,
            'printDate' => now()->format('d F Y'),
            'summary' => [
                'totalSites' => $sites->count(),
                'techNeeded' => round($totalTechNeeded, 2),
                'techExisting' => $totalTechExisting,
                'nonTechNeeded' => $totalNonTechNeeded,
                'nonTechExisting' => $totalNonTechExisting,
            ],
            'detailedSites' => $detailedSites,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $regionFilter = $request->query('region', 'ALL');
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\NationalSummaryExport($regionFilter),
            'Rekapitulasi_SDM_Nasional_' . date('Ymd_His') . '.xlsx'
        );
    }
}
