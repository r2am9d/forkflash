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
            $table->string('name', 255);
            $table->text('url')->nullable();
            $table->text('summary')->nullable();
            $table->integer('servings')->default(1);
            $table->string('video', 255)->nullable(); // video URL
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('ulid'); // Primary index for public queries
            $table->index('user_id'); // Index for user's recipes queries
            $table->index('name');
            $table->index('servings');
            $table->index('difficulty_level');
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
