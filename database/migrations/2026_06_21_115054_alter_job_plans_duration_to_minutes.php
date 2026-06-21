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
        Schema::table('job_plans', function (Blueprint $table) {
            $table->double('duration_minutes')->after('activity_name')->nullable();
        });

        // Copy and convert old data: hours to minutes
        \Illuminate\Support\Facades\DB::statement("UPDATE job_plans SET duration_minutes = duration_hours * 60");

        Schema::table('job_plans', function (Blueprint $table) {
            $table->double('duration_minutes')->nullable(false)->change();
            $table->dropColumn('duration_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_plans', function (Blueprint $table) {
            $table->double('duration_hours')->after('activity_name')->nullable();
        });

        // Copy and convert data back: minutes to hours
        \Illuminate\Support\Facades\DB::statement("UPDATE job_plans SET duration_hours = duration_minutes / 60");

        Schema::table('job_plans', function (Blueprint $table) {
            $table->double('duration_hours')->nullable(false)->change();
            $table->dropColumn('duration_minutes');
        });
    }
};
