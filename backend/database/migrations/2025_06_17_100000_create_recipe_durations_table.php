<?php

declare(strict_types=1);

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
        Schema::create('recipe_durations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('recipe_id')->unique()->constrained('recipes')->onDelete('cascade');
            $table->integer('prep_minutes')->nullable(); // Preparation time in minutes (from JSON "Prep: X minutes")
            $table->integer('cook_minutes')->nullable(); // Active cooking time in minutes (from JSON "Cook: Y minutes")
            $table->integer('total_minutes')->nullable(); // Total time from start to finish (from JSON "Total: Z minutes")
            $table->timestamps();

            // Indexes for performance
            $table->index('total_minutes'); // Common query for filtering by time
            $table->index('prep_minutes'); // Filter by prep time
            $table->index('cook_minutes'); // Filter by cook time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_durations');
    }
};
