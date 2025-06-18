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
        Schema::create('recipe_nutrient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('nutrient_id')->constrained('nutrients')->onDelete('cascade');
            $table->decimal('amount', 10, 4); // Nutrient amount (supports precision for small vitamins)
            $table->boolean('per_serving')->default(true); // Amount is per serving vs total recipe
            $table->string('source', 100)->nullable(); // "calculated", "usda", "manual"
            $table->enum('confidence_level', ['high', 'medium', 'low'])->default('medium');
            $table->integer('sort_order')->default(0); // Display order for this recipe
            $table->boolean('is_active')->default(true); // Can hide specific nutrients per recipe
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Constraints
            $table->unique(['recipe_id', 'nutrient_id']); // One amount per nutrient per recipe

            // Indexes for performance
            $table->index(['recipe_id', 'sort_order']);
            $table->index('nutrient_id');
            $table->index(['recipe_id', 'is_active']);
            $table->index('confidence_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_nutrient');
    }
};
