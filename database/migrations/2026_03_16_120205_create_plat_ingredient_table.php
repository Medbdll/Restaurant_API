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
        Schema::create('plat_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plat_id')->constrained('plats')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredient')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plat_ingredient');
    }
};
