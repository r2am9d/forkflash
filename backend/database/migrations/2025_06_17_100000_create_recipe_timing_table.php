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
        Schema::create('recipe_timing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->unique()->constrained('recipes')->onDelete('cascade');
            $table->integer('prep_minutes')->nullable(); // Preparation time in minutes
            $table->integer('cook_minutes')->nullable(); // Active cooking time in minutes
            $table->integer('total_minutes')->nullable(); // Total time from start to finish
            $table->integer('hands_on_time')->nullable(); // Active hands-on time
            $table->integer('passive_time')->nullable(); // Passive time (baking, marinating, etc.)
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->nullable();
            $table->decimal('servings_time_multiplier', 3, 2)->default(1.0); // Time adjustment for different serving sizes
            $table->text('timing_notes')->nullable(); // Additional timing guidance
            $table->timestamps();

            // Indexes for performance
            $table->index(['total_minutes']);
            $table->index(['prep_minutes']);
            $table->index(['cook_minutes']);
            $table->index(['difficulty_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_timing');
    }
};
