<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Ingredient;
use App\Models\Instruction;
use App\Models\Nutrient;
use App\Models\Recipe;
use App\Models\Unit;
use App\Models\User;
use Database\Factories\ImageFactory;
use Illuminate\Database\Seeder;

final class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating recipes with instructions and images...');

        // Get existing users to assign recipes to
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->error('No users found! Please run UserSeeder first.');

            return;
        }

        // Get available ingredients and nutrients for relationships
        $ingredients = Ingredient::all();
        $nutrients = Nutrient::all();
        $units = Unit::all();

        if ($ingredients->isEmpty()) {
            $this->command->error('No ingredients found! Please run IngredientSeeder first.');

            return;
        }

        if ($nutrients->isEmpty()) {
            $this->command->error('No nutrients found! Please run NutrientSeeder first.');

            return;
        }

        $this->command->info(sprintf('Found %d users, %d ingredients, %d nutrients. Creating recipes...',
            $users->count(), $ingredients->count(), $nutrients->count()));

        // Create 20 recipes with complete data
        Recipe::factory(20)
            ->recycle($users) // Use existing users instead of creating new ones
            ->create()
            ->each(function (Recipe $recipe) use ($ingredients, $nutrients, $units): void {
                $this->createRecipeRelationships($recipe, $ingredients, $nutrients, $units);
            });

        // Create some quick recipes
        Recipe::factory(15)
            ->quick()
            ->recycle($users) // Use existing users
            ->create()
            ->each(function (Recipe $recipe) use ($ingredients, $nutrients, $units): void {
                $this->createRecipeRelationships($recipe, $ingredients, $nutrients, $units, true);
            });

        $this->command->info('Recipe seeding completed!');
        $this->command->info('Created:');
        $this->command->info('- '.Recipe::count().' recipes');
        $this->command->info('- '.Instruction::count().' instructions');
        $this->command->info('- '.Image::count().' images');
        $this->command->info('- '.Recipe::whereHas('ingredients')->count().' recipes with ingredients');
        $this->command->info('- '.Recipe::whereHas('nutrients')->count().' recipes with nutrition data');
    }

    /**
     * Create relationships for a recipe (instructions, images, ingredients, nutrients).
     *
     * @param  mixed  $ingredients
     * @param  mixed  $nutrients
     * @param  mixed  $units
     */
    private function createRecipeRelationships(
        Recipe $recipe,
        $ingredients,
        $nutrients,
        $units,
        bool $isQuick = false
    ): void {
        // Create instructions
        $instructionCount = $isQuick ? fake()->numberBetween(3, 6) : fake()->numberBetween(5, 10);
        $createdIngredients = [];

        // First, create recipe ingredients (3-8 per recipe)
        $ingredientCount = fake()->numberBetween(3, 8);
        $selectedIngredients = $ingredients->random($ingredientCount);
        $attachData = [];

        foreach ($selectedIngredients as $index => $ingredient) {
            // Generate realistic display text
            $quantity = fake()->randomFloat(1, 0.5, 5.0);
            $unit = $units->random()->name ?? fake()->randomElement(['cups', 'tablespoons', 'ounces', 'pieces']);
            $notes = fake()->optional(0.3)->randomElement(['diced', 'chopped', 'minced', 'sliced']);

            $displayText = $quantity.' '.$unit.' '.$ingredient->name;
            if ($notes) {
                $displayText .= ', '.$notes;
            }

            $attachData[$ingredient->id] = [
                'display_text' => $displayText,
                'sort' => $index + 1,
            ];

            $createdIngredients[] = $ingredient;
        }

        // Attach ingredients using pivot relationship
        $recipe->ingredients()->attach($attachData);

        // Create instructions with realistic ingredient references
        for ($i = 1; $i <= $instructionCount; ++$i) {
            // For some steps, reference ingredients from the recipe
            $ingredientIds = null;
            if (fake()->boolean(60) && $createdIngredients !== []) { // 60% chance of having references
                $numReferences = fake()->numberBetween(1, min(3, count($createdIngredients)));
                $selectedForReference = fake()->randomElements($createdIngredients, $numReferences);

                $ingredientIds = [];
                foreach ($selectedForReference as $ingredient) {
                    $ingredientIds[] = $ingredient->id;
                }
            }

            Instruction::factory()
                ->step($i)
                ->create([
                    'recipe_id' => $recipe->id,
                    'ingredient_ids' => $ingredientIds,
                ]);
        }

        // Create images (1-5 per recipe, first one is primary)
        $imageCount = fake()->numberBetween(1, 5);
        for ($i = 0; $i < $imageCount; ++$i) {
            Image::factory()
                ->when($i === 0, fn ($factory): ImageFactory => $factory->primary())
                ->order($i)
                ->create([
                    'recipe_id' => $recipe->id,
                ]);
        }

        // Create nutrition data (use available nutrients, but not more than available)
        $maxNutrients = min(12, $nutrients->count());
        $nutrientCount = fake()->numberBetween(min(5, $nutrients->count()), $maxNutrients);
        $selectedNutrients = $nutrients->random($nutrientCount);

        $nutritionData = [];
        foreach ($selectedNutrients as $nutrient) {
            // Generate realistic nutrition values based on nutrient type
            $amount = $this->generateNutrientAmount($nutrient);
            $percentageDv = fake()->optional(0.7)->randomFloat(0, 1, 200); // 70% chance of having % DV

            $nutritionData[$nutrient->id] = [
                'amount' => $amount,
                'percentage_dv' => $percentageDv,
            ];
        }

        // Attach nutrition data using pivot relationship
        $recipe->nutrients()->attach($nutritionData);
    }

    /**
     * Generate realistic nutrient amounts based on nutrient type.
     */
    private function generateNutrientAmount(Nutrient $nutrient): float
    {
        return match (mb_strtolower($nutrient->name)) {
            'calories' => fake()->randomFloat(1, 150, 800),
            'protein' => fake()->randomFloat(1, 5, 45),
            'total carbohydrates' => fake()->randomFloat(1, 10, 80),
            'dietary fiber' => fake()->randomFloat(1, 2, 15),
            'total fat' => fake()->randomFloat(1, 2, 35),
            'saturated fat' => fake()->randomFloat(1, 1, 15),
            'sodium' => fake()->randomFloat(1, 100, 2000),
            'calcium' => fake()->randomFloat(1, 50, 300),
            'iron' => fake()->randomFloat(1, 1, 10),
            'vitamin c' => fake()->randomFloat(1, 5, 100),
            'vitamin a' => fake()->randomFloat(1, 100, 5000),
            default => fake()->randomFloat(2, 0.1, 10),
        };
    }
}
