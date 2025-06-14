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
            $table->string('name')->unique(); // "cup", "pound", "medium"
            $table->string('display_name'); // "Cup", "Pound", "Medium"
            $table->string('unit_type'); // "volume", "weight", "count", "special"
            $table->boolean('is_standardized')->default(true); // Whether it's a standard cooking unit
            $table->decimal('conversion_factor', 10, 6)->nullable(); // For future unit conversions
            $table->string('abbreviation')->nullable(); // "c", "lb", "med"
            $table->text('description')->nullable(); // "Standard US cooking cup"
            $table->timestamps();

            // Indexes for performance
            $table->index('unit_type');
            $table->index('is_standardized');
            $table->index(['unit_type', 'is_standardized']);
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
