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
        Setting::firstOrCreate(
            ['key' => 'effective_working_hours_per_year'],
            [
                'value' => '1800',
                'type' => 'integer',
                'description' => 'Total jam kerja efektif teknisi per orang dalam 1 tahun (setelah dikurangi cuti, libur, dll).',
            ]
        );

        Setting::firstOrCreate(
            ['key' => 'site_class_thresholds'],
            [
                'value' => json_encode([
                    ['class' => 'A', 'min_hours' => 50000],
                    ['class' => 'B', 'min_hours' => 20000],
                    ['class' => 'C', 'min_hours' => 5000],
                    ['class' => 'D', 'min_hours' => 0],
                ]),
                'type' => 'json',
                'description' => 'Batas minimal total jam pemeliharaan (maintenance hours) untuk menentukan kelas sebuah Site. Kelas A harus berada paling atas dengan min_hours tertinggi.',
            ]
        );
    }
}
