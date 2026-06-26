<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \App\Models\Setting::where('key', 'site_class_thresholds')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Setting::updateOrCreate(
            ['key' => 'site_class_thresholds'],
            [
                'value' => json_encode([
                    ['class' => 'A', 'min_hours' => 50000],
                    ['class' => 'B', 'min_hours' => 20000],
                    ['class' => 'C', 'min_hours' => 5000],
                    ['class' => 'D', 'min_hours' => 0]
                ]),
                'type' => 'json',
                'description' => 'Batas minimal total jam pemeliharaan (maintenance hours) untuk menentukan kelas sebuah Site. Kelas A harus berada paling atas dengan min_hours tertinggi.',
            ]
        );
    }
};
