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
        Schema::create('recipe_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('reaction_type', [
                'like', 'love', 'wow', 'helpful', 'tried_it', 
                'want_to_try', 'bookmarked', 'shared'
            ]);
            $table->text('comment')->nullable();
            $table->integer('rating')->nullable()->comment('1-5 star rating when applicable');
            $table->json('metadata')->nullable()->comment('Additional reaction data like cooking notes');
            $table->boolean('is_public')->default(true);
            $table->timestamp('reacted_at')->useCurrent();
            $table->timestamps();

            // Indexes for performance
            $table->index(['recipe_id', 'reaction_type']);
            $table->index(['user_id', 'reaction_type']);
            $table->index(['recipe_id', 'user_id']);
            $table->index('reacted_at');

            // Unique constraint to prevent duplicate reactions of same type by same user
            $table->unique(['recipe_id', 'user_id', 'reaction_type'], 'unique_recipe_user_reaction');
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
