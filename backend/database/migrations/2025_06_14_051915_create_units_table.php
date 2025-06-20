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
        Schema::create('units', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 50)->unique(); // Shortened for mobile performance
            $table->string('display_name', 100); // Reasonable limit
            $table->enum('unit_type', ['volume', 'weight', 'count', 'size', 'special', 'nutrition']); // Explicit enum
            $table->boolean('is_standardized')->default(true);
            $table->decimal('conversion_factor', 10, 6)->nullable(); // For future unit conversions
            $table->string('abbreviation', 10)->nullable(); // Shortened for mobile
            $table->timestamps();

            // Optimized indexes for mobile queries
            $table->index(['unit_type', 'is_standardized']);
            $table->index('name'); // Fast lookup by name (already unique, but explicit index)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
