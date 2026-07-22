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
            $table->integer('interval_meter')->nullable()->after('type')->comment('Interval HM/Meter khusus untuk tipe MB');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_plans', function (Blueprint $table) {
            $table->dropColumn('interval_meter');
        });
    }
};
