<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
final class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dishTypes = ['pasta', 'chicken', 'beef', 'seafood', 'vegetarian', 'dessert', 'soup', 'salad'];
        $cookingMethods = ['grilled', 'baked', 'fried', 'steamed', 'roasted', 'braised'];
        $difficulties = ['easy', 'medium', 'hard'];

        $dishType = fake()->randomElement($dishTypes);
        $cookingMethod = fake()->randomElement($cookingMethods);

        return [
            'user_id' => User::factory(),
            'name' => ucwords(sprintf('%s %s ', $cookingMethod, $dishType).fake()->word()),
            'url' => fake()->url(),
            'summary' => fake()->optional(0.7)->paragraph(),
            'servings' => fake()->numberBetween(1, 8),
            'video' => fake()->optional(0.2)->url(),
            'difficulty_level' => fake()->optional(0.6)->randomElement($difficulties),
        ];
    }

    /**
     * Create a recipe with a specific difficulty level.
     */
    public function difficulty(string $difficulty): static
    {
        return $this->state(fn (array $attributes): array => [
            'difficulty_level' => $difficulty,
        ]);
    }

    /**
     * Create a quick recipe (easy difficulty, shorter name).
     */
    public function quick(): static
    {
        return $this->state(fn (array $attributes): array => [
            'difficulty_level' => 'easy',
            'name' => 'Quick '.fake()->randomElement(['Pasta', 'Salad', 'Sandwich', 'Stir Fry', 'Soup']),
        ]);
    }
}
