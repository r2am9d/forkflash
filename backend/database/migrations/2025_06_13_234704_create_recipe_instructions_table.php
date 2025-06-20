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
        Schema::create('recipe_instructions', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique(); // ULID for public identification
            $table->foreignId('recipe_id')->constrained('recipes', 'id')->onDelete('cascade');
            $table->integer('sort');
            $table->text('text');
            $table->json('ingredient_ids')->nullable(); // Array of ingredient IDs referenced in this step
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('ulid'); // Primary index for public queries
            $table->index(['recipe_id', 'sort']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_instructions');
    }
};
