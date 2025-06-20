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
        Schema::create('recipe_equipments', function (Blueprint $table) {
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');

            // Composite primary key - no need for separate ID
            $table->primary(['recipe_id', 'equipment_id']);
            
            // Optimized index for lookups
            $table->index(['equipment_id', 'recipe_id']); // For finding recipes by equipment
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_equipments');
    }
};
