<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'man_hours_matrix'],
            [
                'value' => json_encode([
                    'calendar_mode' => 'annual',
                    'shift' => [
                        'hours_per_day' => 7,
                        'days_per_week' => 6,
                        'weeks_per_year' => 48,
                        'active_days_per_year' => 288,
                        'annual_leave_hours' => 84,
                        'sick_leave_hours' => 24,
                        'daily_deductions' => [
                            'meal_rest' => 1.0,
                            'meeting_report_travel' => 1.0,
                            'training_doc' => 0.06,
                            'standby' => 0.5,
                            'skill_factor' => 0,
                            'other' => 0
                        ]
                    ],
                    'non_shift' => [
                        'hours_per_day' => 8,
                        'days_per_week' => 5,
                        'weeks_per_year' => 48,
                        'active_days_per_year' => 240,
                        'annual_leave_hours' => 96,
                        'sick_leave_hours' => 24,
                        'daily_deductions' => [
                            'meal_rest' => 1.0,
                            'meeting_report_travel' => 1.0,
                            'training_doc' => 0.06,
                            'standby' => 0.5,
                            'skill_factor' => 0,
                            'other' => 0
                        ]
                    ]
                ]),
                'type' => 'json',
                'description' => 'Matriks parameter perhitungan jam kerja produktif (Effective Working Hours / Man Hours) untuk teknisi Shift dan Non-Shift.',
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'target_availability'],
            [
                'value' => '85',
                'type' => 'float',
                'description' => 'Target ketersediaan alat (Availability %) untuk menentukan rasio kebutuhan teknisi breakdown maintenance (100% - Target Availability).',
            ]
        );
    }
}
