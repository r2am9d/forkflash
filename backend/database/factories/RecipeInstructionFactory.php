<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeInstruction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecipeInstruction>
 */
final class RecipeInstructionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'sort' => 1,
            'text' => $this->generateInstructionText(),
            'ingredient_ids' => fake()->optional(0.4)->passthrough(
                $this->generateIngredientIds()
            ),
        ];
    }

    /**
     * Create instruction for a specific sort order.
     */
    public function step(int $stepNumber): static
    {
        return $this->state(fn (array $attributes): array => [
            'sort' => $stepNumber,
        ]);
    }

    /**
     * Create instruction with specific ingredient IDs.
     *
     * @param  array<int>  $ingredientIds
     */
    public function withIngredientIds(array $ingredientIds): static
    {
        return $this->state(function (array $attributes) use ($ingredientIds): array {
            return [
                'ingredient_ids' => $ingredientIds,
            ];
        });
    }

    /**
     * Create instruction with no ingredient references.
     */
    public function withoutIngredients(): static
    {
        return $this->state(fn (array $attributes): array => [
            'ingredient_ids' => null,
        ]);
    }

    /**
     * Create instruction with sample ingredient IDs for testing.
     */
    public function withSampleIngredients(): static
    {
        return $this->state(fn (array $attributes): array => [
            'ingredient_ids' => [1, 2, 3], // Will reference actual ingredients when available
        ]);
    }

    /**
     * Create instruction with realistic cooking text that mentions ingredients.
     */
    public function withIngredientText(): static
    {
        return $this->state(fn (array $attributes): array => [
            'text' => 'Heat the olive oil in a large skillet, then add the diced onions and minced garlic. Season with salt and pepper to taste.',
        ]);
    }

    /**
     * Generate realistic cooking instruction text.
     */
    private function generateInstructionText(): string
    {
        $actions = [
            'Heat olive oil in a large skillet over medium-high heat',
            'Add diced onions and cook until translucent, about 5 minutes',
            'Season with salt and pepper to taste',
            'Add minced garlic and cook for another minute until fragrant',
            'Pour in the chicken broth and bring to a simmer',
            'Cover and cook for {time} minutes, stirring occasionally',
            'Remove from heat and let stand for 2-3 minutes',
            'Garnish with fresh parsley and serve immediately',
            'Stir in the heavy cream and cook until thickened',
            'Reduce heat to low and simmer gently',
            'Add the flour and whisk until smooth',
            'Season with paprika, thyme, and black pepper',
            'Combine the ground beef with breadcrumbs and egg',
            'Form the mixture into small meatballs using your hands',
            'Brown the meatballs on all sides in the heated oil',
            'Transfer to a serving platter and keep warm',
            'Deglaze the pan with white wine or broth',
            'Bring the mixture to a rolling boil',
            'Taste and adjust seasoning as needed',
            'Serve hot over rice or pasta',
        ];

        $randomActions = fake()->randomElements($actions, fake()->numberBetween(1, 3));
        $text = implode('. ', $randomActions);

        // Add timing variations
        $text = str_replace('{time}', (string) fake()->numberBetween(5, 25), $text);

        return $text . '.';
    }

    /**
     * Generate sample ingredient IDs for testing.
     *
     * @return array<int>
     */
    private function generateIngredientIds(): array
    {
        // Generate 1-3 random ingredient IDs
        $count = fake()->numberBetween(1, 3);
        $ingredientIds = [];
        
        for ($i = 0; $i < $count; $i++) {
            $ingredientIds[] = fake()->numberBetween(1, 20); // Assume we have ingredients with IDs 1-20
        }
        
        return array_unique($ingredientIds); // Remove duplicates
    }
}
