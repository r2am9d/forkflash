<?php

namespace Database\Factories;

use App\Models\RecipeReaction;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeReaction>
 */
class RecipeReactionFactory extends Factory
{
    protected $model = RecipeReaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['like', 'love', 'bookmark', 'tried']),
        ];
    }

    // State methods for specific reaction types
    public function like(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'like',
        ]);
    }

    public function love(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'love',
        ]);
    }

    public function bookmark(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'bookmark',
        ]);
    }

    public function tried(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'tried',
        ]);
    }

    /**
     * Create a positive reaction (like, love, tried)
     */
    public function positive(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => fake()->randomElement(['like', 'love', 'tried']),
        ]);
    }

    /**
     * Create an engagement reaction (bookmark)
     */
    public function engagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'bookmark',
        ]);
    }
}
