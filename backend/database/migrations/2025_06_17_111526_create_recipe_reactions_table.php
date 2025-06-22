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
        Schema::create('recipe_reactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['like', 'love', 'bookmark', 'tried'])->default('like');
            $table->timestamps();

            // Performance indexes
            $table->index(['recipe_id', 'type']);
            $table->index(['user_id', 'type']);
            $table->index('created_at');

            // One reaction per user per recipe (user can change reaction type)
            $table->unique(['recipe_id', 'user_id'], 'unique_user_recipe_reaction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_reactions');
    }
};
