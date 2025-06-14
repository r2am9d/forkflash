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
        Schema::create('recipes', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique(); // ULID for public identification
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('name');
            $table->text('url')->nullable();
            $table->text('image')->nullable();
            $table->text('summary')->nullable();
            $table->string('servings')->nullable();
            $table->json('info')->nullable(); // prep time, cook time, total time
            $table->json('ingredients'); // array of ingredients
            $table->json('equipments')->nullable(); // array of equipment
            $table->json('notes')->nullable(); // array of notes
            $table->json('nutrition')->nullable(); // array of nutrition info
            $table->json('tips')->nullable(); // array of tips
            $table->text('video')->nullable(); // video URL
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('ulid'); // Primary index for public queries
            $table->index('user_id'); // Index for user's recipes queries
            $table->index('name');
            $table->index('servings');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
