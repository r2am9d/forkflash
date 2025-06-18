<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeNutrient>
 */
class RecipeNutrientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id' => \App\Models\Recipe::factory(),
            'nutrient_id' => \App\Models\Nutrient::factory(),
            'amount' => $this->faker->randomFloat(2, 0.1, 1000), // Realistic nutrient amounts
            'per_serving' => $this->faker->boolean(80), // 80% per serving
            'source' => $this->faker->randomElement(['calculated', 'usda', 'manual', 'estimated']),
            'confidence_level' => $this->faker->randomElement(['high', 'medium', 'low']),
            'sort_order' => $this->faker->numberBetween(0, 50),
            'is_active' => $this->faker->boolean(95), // 95% active
            'verified_at' => $this->faker->optional(30)->dateTimeThisYear(), // 30% verified
            'notes' => $this->faker->optional(20)->sentence(), // 20% have notes
        ];
    }

    /**
     * Indicate that the nutrient data is for calories.
     */
    public function calories(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $this->faker->numberBetween(100, 800),
            'confidence_level' => 'high',
            'source' => 'calculated',
        ]);
    }

    /**
     * Indicate that the nutrient data is for protein.
     */
    public function protein(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $this->faker->randomFloat(1, 5, 50),
            'confidence_level' => 'high',
            'source' => 'calculated',
        ]);
    }

    /**
     * Indicate that the nutrient data is for carbohydrates.
     */
    public function carbs(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $this->faker->randomFloat(1, 10, 100),
            'confidence_level' => 'high',
            'source' => 'calculated',
        ]);
    }

    /**
     * Indicate that the nutrient data is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => $this->faker->dateTimeThisYear(),
            'confidence_level' => 'high',
        ]);
    }

    /**
     * Indicate that the nutrient data is from USDA.
     */
    public function fromUsda(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => 'usda',
            'confidence_level' => 'high',
            'verified_at' => $this->faker->dateTimeThisYear(),
        ]);
    }

    /**
     * Indicate that the nutrient data is manually entered.
     */
    public function manual(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => 'manual',
            'confidence_level' => $this->faker->randomElement(['medium', 'low']),
            'notes' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the nutrient data is inactive/hidden.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
