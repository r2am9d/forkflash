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
            'step_number' => 1,
            'text' => $this->generateInstructionText(),
            'ingredients' => fake()->optional(0.3)->randomElements([
                'salt to taste',
                'pepper to taste',
                '1 tablespoon oil',
                '1/4 cup water',
                'fresh herbs for garnish',
            ], fake()->numberBetween(1, 2)),
        ];
    }

    /**
     * Create instruction for a specific step number.
     */
    public function step(int $stepNumber): static
    {
        return $this->state(fn (array $attributes): array => [
            'step_number' => $stepNumber,
        ]);
    }

    /**
     * Create instruction with specific ingredients.
     *
     * @param  array<mixed>  $ingredients
     */
    public function withIngredients(array $ingredients): static
    {
        return $this->state(fn (array $attributes): array => [
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * Generate realistic cooking instruction text.
     */
    private function generateInstructionText(): string
    {
        $actions = [
            'Heat oil in a large skillet over medium-high heat',
            'Add onions and cook until translucent, about 5 minutes',
            'Season with salt and pepper to taste',
            'Add garlic and cook for another minute until fragrant',
            'Pour in the liquid and bring to a simmer',
            'Cover and cook for {time} minutes, stirring occasionally',
            'Remove from heat and let stand for 2-3 minutes',
            'Garnish with fresh herbs and serve immediately',
            'Stir in the cream and cook until thickened',
            'Reduce heat to low and simmer gently',
        ];

        $instruction = fake()->randomElement($actions);

        // Replace placeholder with random cooking time
        $instruction = str_replace('{time}', (string) fake()->numberBetween(10, 30), $instruction);

        return $instruction;
    }
}
