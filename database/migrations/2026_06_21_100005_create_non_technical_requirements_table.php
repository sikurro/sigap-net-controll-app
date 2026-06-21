<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('non_technical_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_class_id')->constrained('site_classes')->cascadeOnDelete();
            $table->foreignId('non_technical_position_id')->constrained('non_technical_positions')->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('non_technical_requirements');
    }
};
