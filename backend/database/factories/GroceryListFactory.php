<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\GroceryList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GroceryList>
 */
final class GroceryListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $listTypes = [
            'Weekly Shopping',
            'Party Supplies',
            'Dinner Ingredients',
            'Breakfast Items',
            'Quick Lunch',
            'Healthy Snacks',
            'BBQ Essentials',
            'Holiday Baking',
            'Date Night Dinner',
            'Family Picnic',
        ];

        $categories = ['Produce', 'Dairy', 'Meat', 'Pantry', 'Frozen', 'Beverages'];

        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement($listTypes),
            'description' => fake()->optional(0.6)->sentence(),
            'is_template' => fake()->boolean(20), // 20% chance of being template
            'is_shared' => fake()->boolean(30), // 30% chance of being shared
            'shared_with' => fake()->optional(0.3)->randomElements([1, 2, 3, 4, 5], fake()->numberBetween(1, 3)),
            'tags' => fake()->optional(0.5)->randomElements($categories, fake()->numberBetween(1, 3)),
            'completed_at' => fake()->optional(0.3)->dateTimeBetween('-1 week', 'now'),
            'metadata' => fake()->optional(0.4)->randomElements([
                'store_preference' => fake()->randomElement(['Walmart', 'Target', 'Whole Foods', 'Kroger']),
                'budget_limit' => fake()->randomFloat(2, 50, 200),
                'shopping_date' => fake()->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            ]),
        ];
    }

    /**
     * Create a template list.
     */
    public function template(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_template' => true,
            'name' => 'Template: '.fake()->randomElement([
                'Weekly Essentials',
                'Party Planning',
                'Healthy Meal Prep',
                'Quick Dinners',
                'Breakfast Staples',
            ]),
            'description' => 'Reusable shopping list template',
            'completed_at' => null,
        ]);
    }

    /**
     * Create a shared list.
     */
    public function shared(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_shared' => true,
            'shared_with' => fake()->randomElements([1, 2, 3, 4, 5], fake()->numberBetween(1, 3)),
        ]);
    }

    /**
     * Create a completed list.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'completed_at' => fake()->dateTimeBetween('-2 weeks', 'now'),
        ]);
    }

    /**
     * Create an active (uncompleted) list.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'completed_at' => null,
        ]);
    }

    /**
     * Create a list with specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes): array => [
            'name' => $name,
        ]);
    }

    /**
     * Create a list for a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_id' => $user->id,
        ]);
    }
}
