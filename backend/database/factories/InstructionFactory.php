<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Instruction;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Instruction>
 */
final class InstructionFactory extends Factory
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
        return $this->state(fn (array $attributes): array => [
            'ingredient_ids' => $ingredientIds,
        ]);
    }

    /**
     * Create instruction without ingredient references.
     */
    public function withoutIngredients(): static
    {
        return $this->state(fn (array $attributes): array => [
            'ingredient_ids' => null,
        ]);
    }

    /**
     * Generate realistic cooking instruction text.
     */
    private function generateInstructionText(): string
    {
        $instructionTemplates = [
            // Prep instructions
            'Heat {oil_type} in a {cookware} over {heat_level} heat.',
            'Chop the {ingredient} into {size} pieces.',
            'Dice the {vegetable} and set aside.',
            'Mince the {aromatics} finely.',
            'Season with {seasoning} to taste.',

            // Cooking instructions
            'Add {ingredient} to the {cookware} and cook for {time} minutes.',
            'Sauté the {ingredient} until {doneness_indicator}.',
            'Bring the mixture to a {cooking_method}, then reduce heat.',
            'Stir in the {ingredient} and cook until {texture}.',
            'Cover and simmer for {time} until {doneness}.',

            // Finishing instructions
            'Remove from heat and let cool for {time} minutes.',
            'Garnish with {garnish} before serving.',
            'Serve immediately while hot.',
            'Allow to rest for {time} before slicing.',
            'Taste and adjust seasoning as needed.',
        ];

        $replacements = [
            '{oil_type}' => fake()->randomElement(['olive oil', 'butter', 'vegetable oil', 'canola oil']),
            '{cookware}' => fake()->randomElement(['large skillet', 'saucepan', 'Dutch oven', 'wok', 'cast iron pan']),
            '{heat_level}' => fake()->randomElement(['medium', 'medium-high', 'low', 'high']),
            '{ingredient}' => fake()->randomElement(['onions', 'garlic', 'chicken', 'beef', 'vegetables']),
            '{vegetable}' => fake()->randomElement(['onions', 'carrots', 'celery', 'bell peppers']),
            '{aromatics}' => fake()->randomElement(['garlic', 'ginger', 'shallots']),
            '{seasoning}' => fake()->randomElement(['salt and pepper', 'herbs', 'spices']),
            '{size}' => fake()->randomElement(['small', 'medium', 'large', '1-inch']),
            '{time}' => fake()->numberBetween(2, 15),
            '{doneness_indicator}' => fake()->randomElement(['golden brown', 'translucent', 'fragrant', 'tender']),
            '{cooking_method}' => fake()->randomElement(['boil', 'simmer', 'gentle simmer']),
            '{texture}' => fake()->randomElement(['tender', 'soft', 'heated through']),
            '{doneness}' => fake()->randomElement(['tender', 'cooked through', 'fork-tender']),
            '{garnish}' => fake()->randomElement(['fresh herbs', 'green onions', 'parsley', 'cilantro']),
        ];

        $template = fake()->randomElement($instructionTemplates);

        return str_replace(
            array_keys($replacements),
            array_map('strval', array_values($replacements)),
            $template
        );
    }

    /**
     * Generate realistic ingredient IDs that might be referenced.
     *
     * @return array<int>
     */
    private function generateIngredientIds(): array
    {
        $count = fake()->numberBetween(1, 3);
        $ingredientIds = range(1, 20); // Assume we have 20 common ingredients

        return fake()->randomElements($ingredientIds, $count);
    }
}
