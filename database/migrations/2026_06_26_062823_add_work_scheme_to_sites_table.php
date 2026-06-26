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
        Schema::table('sites', function (Blueprint $table) {
            $table->enum('work_scheme', ['Shift', 'Non-Shift'])->default('Non-Shift')->after('site_class');
        });

        \App\Models\Setting::where('key', 'effective_working_hours_per_year')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('work_scheme');
        });
    }
};
