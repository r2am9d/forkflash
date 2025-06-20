<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Reaction;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reaction>
 */
final class ReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reactionTypes = ['like', 'love', 'bookmark', 'tried'];
        
        return [
            'recipe_id' => Recipe::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement($reactionTypes),
        ];
    }

    /**
     * Create specific reaction types.
     */
    public function like(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'like',
        ]);
    }

    public function love(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'love',
        ]);
    }

    public function bookmark(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'bookmark',
        ]);
    }

    public function tried(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'tried',
        ]);
    }

    /**
     * Create reactions for specific recipe and user.
     */
    public function forRecipe(int $recipeId): static
    {
        return $this->state(fn (array $attributes): array => [
            'recipe_id' => $recipeId,
        ]);
    }

    public function byUser(int $userId): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_id' => $userId,
        ]);
    }

    /**
     * Create engagement reactions (bookmark, tried).
     */
    public function engagement(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => fake()->randomElement(['bookmark', 'tried']),
        ]);
    }

    /**
     * Create positive reactions (like, love, tried).
     */
    public function positive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => fake()->randomElement(['like', 'love', 'tried']),
        ]);
    }
}
