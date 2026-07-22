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
        Schema::table('site_equipments', function (Blueprint $table) {
            $table->decimal('utilization_rate', 5, 2)->default(0.00)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_equipments', function (Blueprint $table) {
            $table->dropColumn('utilization_rate');
        });
    }
};
