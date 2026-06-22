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
            $table->string('site_class')->nullable()->after('status');
            $table->float('total_maintenance_hours')->default(0)->after('site_class');
            $table->float('technical_staff_needed')->default(0)->after('total_maintenance_hours');
            $table->integer('non_technical_staff_needed')->default(0)->after('technical_staff_needed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn([
                'site_class',
                'total_maintenance_hours',
                'technical_staff_needed',
                'non_technical_staff_needed'
            ]);
        });
    }
};
