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
        Schema::table('equipment_types', function (Blueprint $table) {
            $table->integer('weight')->default(0)->after('code');
            $table->string('category')->default('Others')->after('weight');
        });

        Schema::table('site_classes', function (Blueprint $table) {
            $table->integer('min_weight')->default(0)->after('name');
            $table->integer('max_weight')->nullable()->after('min_weight');
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->integer('total_weight')->default(0)->after('site_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('total_weight');
        });

        Schema::table('site_classes', function (Blueprint $table) {
            $table->dropColumn(['min_weight', 'max_weight']);
        });

        Schema::table('equipment_types', function (Blueprint $table) {
            $table->dropColumn(['weight', 'category']);
        });
    }
};
