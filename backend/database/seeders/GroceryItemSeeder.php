<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\GroceryItem;
use App\Models\GroceryList;
use App\Models\Recipe;
use App\Services\IngredientParser;
use Illuminate\Database\Seeder;

final class GroceryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating grocery items...');

        $groceryLists = GroceryList::all();

        if ($groceryLists->isEmpty()) {
            $this->command->error('No grocery lists found! Please run GroceryListSeeder first.');

            return;
        }

        $ingredientParser = new IngredientParser();

        $groceryLists->each(function (GroceryList $groceryList) use ($ingredientParser): void {
            // Create items from attached recipes
            $this->createItemsFromRecipes($groceryList, $ingredientParser);

            // Add some manual items (not from recipes)
            $this->createManualItems($groceryList);
        });

        $this->command->info('Grocery item seeding completed!');
        $this->command->info('Created:');
        $this->command->info('- '.GroceryItem::count().' grocery items');
        $this->command->info('- '.GroceryItem::fromRecipe()->count().' items from recipes');
        $this->command->info('- '.GroceryItem::manual()->count().' manual items');
        $this->command->info('- '.GroceryItem::checked()->count().' checked items');
    }

    /**
     * Create grocery items from attached recipes.
     */
    private function createItemsFromRecipes(GroceryList $groceryList, IngredientParser $parser): void
    {
        $groceryList->recipes->each(function (Recipe $recipe) use ($groceryList, $parser): void {
            // Use the actual ingredients from recipe
            /** @var array<mixed>|string $selectedIngredients */
            $selectedIngredients = $recipe->ingredients;
            $servings = $recipe->pivot->servings ?? 1;

            // Decode JSON string if it's stored as JSON
            if (is_string($selectedIngredients)) {
                $selectedIngredients = json_decode($selectedIngredients, true) ?? [];
            }

            if (! is_array($selectedIngredients) || $selectedIngredients === []) {
                return;
            }

            $createdItemIds = [];

            foreach ($selectedIngredients as $index => $ingredient) {
                $parsed = $parser->parseIngredient($ingredient);

                $groceryItemData = $parser->toGroceryItemData($parsed, $groceryList->id, $recipe->id);

                // Adjust quantity for servings
                if (is_numeric($groceryItemData['quantity']) && $servings > 1) {
                    $groceryItemData['quantity'] *= $servings;
                }

                $groceryItem = GroceryItem::create([
                    'grocery_list_id' => $groceryItemData['grocery_list_id'],
                    'name' => $groceryItemData['name'],
                    'quantity' => $groceryItemData['quantity'],
                    'unit_id' => $groceryItemData['unit_id'],
                    'recipe_id' => $groceryItemData['recipe_id'],
                    'is_checked' => fake()->boolean(20), // 20% chance already checked
                    'checked_at' => fake()->boolean(20) ? fake()->dateTimeBetween('-2 days', 'now') : null,
                    'category' => $this->guessCategory($parsed['name']),
                    'sort_order' => $index,
                    'estimated_price' => fake()->optional(0.6)->randomFloat(2, 1, 15),
                    'notes' => fake()->optional(0.2)->randomElement([
                        'From recipe: '.$recipe->name,
                        'Adjust for '.$servings.' servings',
                        'Check if we have this',
                    ]),
                    'metadata' => array_merge($groceryItemData['metadata'] ?? [], [
                        'generated_from_recipe' => true,
                        'recipe_name' => $recipe->name,
                        'servings_multiplier' => $servings,
                    ]),
                ]);

                // The observer will automatically add this item to selected_item_ids
                // No need to manually track it here since the observer handles it
            }
        });
    }

    /**
     * Create manual grocery items (not from recipes).
     */
    private function createManualItems(GroceryList $groceryList): void
    {
        // Add 3-8 manual items per list
        $manualItemCount = fake()->numberBetween(3, 8);

        for ($i = 0; $i < $manualItemCount; ++$i) {
            GroceryItem::factory()
                ->unchecked()
                ->create([
                    'grocery_list_id' => $groceryList->id,
                    'recipe_id' => null,
                    'sort_order' => 1000 + $i, // Put manual items at the end
                    'metadata' => [
                        'manually_added' => true,
                        'added_by_user' => true,
                    ],
                ]);
        }
    }

    /**
     * Guess the category of an ingredient/item.
     */
    private function guessCategory(string $itemName): string
    {
        $itemName = mb_strtolower($itemName);

        // Produce
        if (preg_match('/\b(apple|banana|orange|tomato|onion|garlic|carrot|potato|lettuce|spinach|broccoli|pepper|cucumber|celery|avocado|lemon|lime|herb|parsley|cilantro|basil|vegetable|fruit)\b/', $itemName)) {
            return 'Produce';
        }

        // Meat & Seafood
        if (preg_match('/\b(chicken|beef|pork|lamb|turkey|fish|salmon|tuna|shrimp|bacon|sausage|ham|ground|steak|breast|thigh|meat|seafood)\b/', $itemName)) {
            return 'Meat & Seafood';
        }

        // Dairy
        if (preg_match('/\b(milk|cheese|butter|cream|yogurt|egg|dairy)\b/', $itemName)) {
            return 'Dairy';
        }

        // Pantry
        if (preg_match('/\b(flour|sugar|salt|pepper|oil|vinegar|sauce|pasta|rice|bread|cereal|can|jar|spice|seasoning|baking|condiment)\b/', $itemName)) {
            return 'Pantry';
        }

        // Frozen
        if (preg_match('/\b(frozen|ice)\b/', $itemName)) {
            return 'Frozen';
        }

        // Beverages
        if (preg_match('/\b(juice|soda|water|coffee|tea|beer|wine|drink|beverage)\b/', $itemName)) {
            return 'Beverages';
        }

        // Bakery
        if (preg_match('/\b(bread|bagel|muffin|cake|cookie|pie|bakery)\b/', $itemName)) {
            return 'Bakery';
        }

        // Default
        return 'Other';
    }
}
