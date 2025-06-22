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
        Schema::create('nutrients', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100)->unique(); // "Serving", "Calories", "Protein"
            $table->string('slug', 120)->unique(); // "serving", "calories", "protein"
            $table->foreignId('unit_id')->constrained('units')->onDelete('restrict'); // Reference to units table
            $table->timestamps();

            // Indexes for performance
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrients');
    }
};
