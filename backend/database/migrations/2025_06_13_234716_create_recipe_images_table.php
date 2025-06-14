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
        Schema::create('recipe_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes', 'id')->onDelete('cascade');
            $table->text('url');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['recipe_id', 'is_primary']);
            $table->index(['recipe_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_images');
    }
};
