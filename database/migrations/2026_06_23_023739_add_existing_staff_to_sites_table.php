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
            $table->integer('existing_technical_staff')->default(0)->after('non_technical_staff_needed');
            $table->integer('existing_non_technical_staff')->default(0)->after('existing_technical_staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['existing_technical_staff', 'existing_non_technical_staff']);
        });
    }
};
