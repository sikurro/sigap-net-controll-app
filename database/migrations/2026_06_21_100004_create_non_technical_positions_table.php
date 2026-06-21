<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('non_technical_positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['fungsional', 'non-fungsional']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('non_technical_positions');
    }
};
