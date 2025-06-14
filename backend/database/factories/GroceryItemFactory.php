<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CookingUnit;
use App\Models\GroceryItem;
use App\Models\GroceryList;
use App\Models\Recipe;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GroceryItem>
 */
final class GroceryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Realistic grocery items with proper units
        $groceryItems = [
            // Produce
            ['name' => 'Bananas', 'category' => 'Produce', 'quantity' => [1, 2, 3], 'unit' => 'bunch'],
            ['name' => 'Apples', 'category' => 'Produce', 'quantity' => [2, 3, 4, 5], 'unit' => 'pound'],
            ['name' => 'Onions', 'category' => 'Produce', 'quantity' => [1, 2, 3], 'unit' => 'medium'],
            ['name' => 'Tomatoes', 'category' => 'Produce', 'quantity' => [2, 3, 4], 'unit' => 'large'],
            ['name' => 'Carrots', 'category' => 'Produce', 'quantity' => [1, 2], 'unit' => 'bag'],
            ['name' => 'Lettuce', 'category' => 'Produce', 'quantity' => [1, 2], 'unit' => 'head'],
            ['name' => 'Garlic', 'category' => 'Produce', 'quantity' => [1, 2], 'unit' => 'head'],

            // Dairy
            ['name' => 'Milk', 'category' => 'Dairy', 'quantity' => [1, 2], 'unit' => 'gallon'],
            ['name' => 'Eggs', 'category' => 'Dairy', 'quantity' => [1, 2], 'unit' => 'dozen'],
            ['name' => 'Cheese', 'category' => 'Dairy', 'quantity' => [8, 12, 16], 'unit' => 'ounce'],
            ['name' => 'Butter', 'category' => 'Dairy', 'quantity' => [1, 2], 'unit' => 'package'],
            ['name' => 'Yogurt', 'category' => 'Dairy', 'quantity' => [1, 2], 'unit' => 'container'],

            // Meat & Seafood
            ['name' => 'Chicken Breast', 'category' => 'Meat', 'quantity' => [1, 2, 3], 'unit' => 'pound'],
            ['name' => 'Ground Beef', 'category' => 'Meat', 'quantity' => [1, 2], 'unit' => 'pound'],
            ['name' => 'Salmon Fillet', 'category' => 'Seafood', 'quantity' => [1, 1.5, 2], 'unit' => 'pound'],
            ['name' => 'Bacon', 'category' => 'Meat', 'quantity' => [1, 2], 'unit' => 'package'],

            // Pantry
            ['name' => 'Rice', 'category' => 'Pantry', 'quantity' => [1, 2], 'unit' => 'bag'],
            ['name' => 'Pasta', 'category' => 'Pantry', 'quantity' => [1, 2], 'unit' => 'box'],
            ['name' => 'Olive Oil', 'category' => 'Pantry', 'quantity' => [1], 'unit' => 'bottle'],
            ['name' => 'Flour', 'category' => 'Pantry', 'quantity' => [2, 3, 5], 'unit' => 'pound'],
            ['name' => 'Sugar', 'category' => 'Pantry', 'quantity' => [1, 2], 'unit' => 'bag'],
            ['name' => 'Salt', 'category' => 'Pantry', 'quantity' => [1], 'unit' => 'container'],
            ['name' => 'Black Pepper', 'category' => 'Pantry', 'quantity' => null, 'unit' => 'to taste'],

            // Beverages
            ['name' => 'Orange Juice', 'category' => 'Beverages', 'quantity' => [1, 2], 'unit' => 'bottle'],
            ['name' => 'Coffee', 'category' => 'Beverages', 'quantity' => [1], 'unit' => 'bag'],
            ['name' => 'Tea Bags', 'category' => 'Beverages', 'quantity' => [1], 'unit' => 'box'],

            // Frozen
            ['name' => 'Frozen Peas', 'category' => 'Frozen', 'quantity' => [1, 2], 'unit' => 'bag'],
            ['name' => 'Ice Cream', 'category' => 'Frozen', 'quantity' => [1], 'unit' => 'container'],
        ];

        $item = fake()->randomElement($groceryItems);
        $quantity = is_array($item['quantity']) ? fake()->randomElement($item['quantity']) : $item['quantity'];
        $unitName = $item['unit'];

        return [
            'grocery_list_id' => GroceryList::factory(),
            'name' => $item['name'],
            'category' => $item['category'],
            'quantity' => $quantity,
            'unit_id' => fn () =>
                // Find existing unit or create it
                Unit::firstOrCreate(
                    ['name' => mb_strtolower((string) $unitName)],
                    [
                        'display_name' => ucfirst((string) $unitName),
                        'unit_type' => CookingUnit::getCategory($unitName) ?? 'other',
                        'is_standardized' => CookingUnit::isStandard($unitName),
                        'abbreviation' => $this->getUnitAbbreviation($unitName),
                        'description' => sprintf('Standard %s unit', $unitName),
                    ]
                )->id,
            'notes' => fake()->optional(0.3)->randomElement([
                'Organic preferred',
                'Brand: '.fake()->company(),
                'Fresh only',
                'Check expiration date',
                'On sale this week',
            ]),
            'is_checked' => fake()->boolean(30), // 30% chance already checked
            'checked_at' => fake()->boolean(30) ? fake()->dateTimeBetween('-1 day', 'now') : null,
            'sort_order' => fake()->numberBetween(0, 100),
            'estimated_price' => fake()->optional(0.7)->randomFloat(2, 1, 25),
            'recipe_id' => fake()->optional(0.4)->randomElement([1, 2, 3, 4, 5]), // 40% chance from recipe
            'metadata' => fake()->optional(0.3)->randomElements([
                'aisle' => fake()->randomElement(['Produce', 'Dairy', 'Meat', 'Frozen', 'Pantry']),
                'brand_preference' => fake()->company(),
                'alternative' => fake()->word(),
            ]),
        ];
    }

    /**
     * Create a checked item.
     */
    public function checked(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_checked' => true,
            'checked_at' => fake()->dateTimeBetween('-1 day', 'now'),
        ]);
    }

    /**
     * Create an unchecked item.
     */
    public function unchecked(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_checked' => false,
            'checked_at' => null,
        ]);
    }

    /**
     * Create an item from a recipe.
     */
    public function fromRecipe(Recipe $recipe): static
    {
        return $this->state(fn (array $attributes): array => [
            'recipe_id' => $recipe->id,
        ]);
    }

    /**
     * Create an item with specific category.
     */
    public function category(string $category): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => $category,
        ]);
    }

    /**
     * Create an item with specific unit.
     */
    public function withUnit(string $unitName): static
    {
        return $this->state(fn (array $attributes): array => [
            'unit_id' => fn () => Unit::firstOrCreate(
                ['name' => mb_strtolower($unitName)],
                [
                    'display_name' => ucfirst($unitName),
                    'unit_type' => CookingUnit::getCategory($unitName) ?? 'other',
                    'is_standardized' => CookingUnit::isStandard($unitName),
                    'abbreviation' => $this->getUnitAbbreviation($unitName),
                    'description' => sprintf('Standard %s unit', $unitName),
                ]
            )->id,
        ]);
    }

    /**
     * Create an item with estimated price.
     */
    public function withPrice(float $price): static
    {
        return $this->state(fn (array $attributes): array => [
            'estimated_price' => $price,
        ]);
    }

    /**
     * Get abbreviation for a unit name.
     */
    private function getUnitAbbreviation(string $unit): ?string
    {
        $abbreviations = [
            'tablespoon' => 'tbsp',
            'teaspoon' => 'tsp',
            'fluid ounce' => 'fl oz',
            'pound' => 'lb',
            'ounce' => 'oz',
            'gram' => 'g',
            'kilogram' => 'kg',
            'milliliter' => 'ml',
            'liter' => 'l',
            'package' => 'pkg',
            'small' => 'sm',
            'medium' => 'med',
            'large' => 'lg',
        ];

        return $abbreviations[mb_strtolower($unit)] ?? null;
    }
}
