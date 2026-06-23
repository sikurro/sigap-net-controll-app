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
            $table->unsignedBigInteger('category_id')->nullable()->after('weight');
            $table->foreign('category_id')->references('id')->on('equipment_category_baselines')->onDelete('set null');
        });

        // Migrate existing data
        $equipments = \Illuminate\Support\Facades\DB::table('equipment_types')->get();
        foreach ($equipments as $eq) {
            $baseline = \Illuminate\Support\Facades\DB::table('equipment_category_baselines')->where('category', $eq->category)->first();
            if ($baseline) {
                \Illuminate\Support\Facades\DB::table('equipment_types')->where('id', $eq->id)->update(['category_id' => $baseline->id]);
            }
        }

        Schema::table('equipment_types', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_types', function (Blueprint $table) {
            $table->string('category')->default('Others')->after('weight');
        });

        // Restore existing data
        $equipments = \Illuminate\Support\Facades\DB::table('equipment_types')->get();
        foreach ($equipments as $eq) {
            $baseline = \Illuminate\Support\Facades\DB::table('equipment_category_baselines')->where('id', $eq->category_id)->first();
            if ($baseline) {
                \Illuminate\Support\Facades\DB::table('equipment_types')->where('id', $eq->id)->update(['category' => $baseline->category]);
            }
        }

        Schema::table('equipment_types', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
