<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Nutrient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Nutrient>
 */
final class NutrientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nutrients = [
            // Macronutrients
            ['name' => 'Calories', 'unit' => 'kcal', 'category' => 'macronutrient', 'daily_value' => 2000],
            ['name' => 'Protein', 'unit' => 'g', 'category' => 'macronutrient', 'daily_value' => 50],
            ['name' => 'Total Carbohydrates', 'unit' => 'g', 'category' => 'macronutrient', 'daily_value' => 300],
            ['name' => 'Total Fat', 'unit' => 'g', 'category' => 'macronutrient', 'daily_value' => 65],
            ['name' => 'Dietary Fiber', 'unit' => 'g', 'category' => 'macronutrient', 'daily_value' => 25],
            ['name' => 'Sugar', 'unit' => 'g', 'category' => 'macronutrient', 'daily_value' => null],

            // Vitamins
            ['name' => 'Vitamin A', 'unit' => 'IU', 'category' => 'vitamin', 'daily_value' => 5000],
            ['name' => 'Vitamin C', 'unit' => 'mg', 'category' => 'vitamin', 'daily_value' => 60],
            ['name' => 'Vitamin D', 'unit' => 'IU', 'category' => 'vitamin', 'daily_value' => 400],
            ['name' => 'Vitamin E', 'unit' => 'IU', 'category' => 'vitamin', 'daily_value' => 30],

            // Minerals
            ['name' => 'Calcium', 'unit' => 'mg', 'category' => 'mineral', 'daily_value' => 1000],
            ['name' => 'Iron', 'unit' => 'mg', 'category' => 'mineral', 'daily_value' => 18],
            ['name' => 'Sodium', 'unit' => 'mg', 'category' => 'mineral', 'daily_value' => 2300],
            ['name' => 'Potassium', 'unit' => 'mg', 'category' => 'mineral', 'daily_value' => 3500],

            // Other
            ['name' => 'Cholesterol', 'unit' => 'mg', 'category' => 'other', 'daily_value' => 300],
            ['name' => 'Saturated Fat', 'unit' => 'g', 'category' => 'other', 'daily_value' => 20],
        ];

        $nutrient = $this->faker->randomElement($nutrients);

        return [
            'name' => $nutrient['name'],
            'slug' => Str::slug($nutrient['name']),
            'unit' => $nutrient['unit'],
            'display_name' => $nutrient['name'],
            'category' => $nutrient['category'],
            'description' => $this->faker->optional()->sentence(),
            'daily_value' => $nutrient['daily_value'],
        ];
    }

    /**
     * Indicate that the nutrient is a macronutrient.
     */
    public function macronutrient(): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => 'macronutrient',
        ]);
    }

    /**
     * Indicate that the nutrient is a vitamin.
     */
    public function vitamin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => 'vitamin',
        ]);
    }

    /**
     * Indicate that the nutrient is a mineral.
     */
    public function mineral(): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => 'mineral',
        ]);
    }
}
