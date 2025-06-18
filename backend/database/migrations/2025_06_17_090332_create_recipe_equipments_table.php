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
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
            $table->boolean('is_required')->default(true); // Some equipment might be optional
            $table->text('notes')->nullable(); // Usage notes or alternatives
            $table->integer('display_order')->default(0); // Order to display equipment
            $table->timestamps();

            // Indexes for performance
            $table->index(['recipe_id']);
            $table->index(['equipment_id']);
            $table->index(['recipe_id', 'display_order']);
            
            // Prevent duplicate equipment assignments per recipe
            $table->unique(['recipe_id', 'equipment_id']);
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
