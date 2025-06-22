<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating ingredients using hierarchical categories...');

        // Get all leaf categories (categories with no children)
        $leafCategories = IngredientCategory::leaves()->get()->keyBy('slug');

        if ($leafCategories->isEmpty()) {
            $this->command->error('No leaf categories found! Please run IngredientCategorySeeder first.');

            return;
        }

        // Ingredients organized by leaf category slug
        $ingredients = [
            // Beef (under Protein > Beef)
            'beef' => [
                ['name' => 'Ground Beef', 'alternatives' => ['ground turkey', 'ground pork', 'ground chicken']],
                ['name' => 'Corned Beef', 'alternatives' => ['pastrami', 'beef brisket']],
                ['name' => 'Ribeye Steak', 'alternatives' => ['sirloin steak', 'filet mignon']],
                ['name' => 'Beef Chuck', 'alternatives' => ['beef shoulder', 'beef round']],
                ['name' => 'Beef Brisket', 'alternatives' => ['chuck roast', 'short ribs']],
            ],

            // Chicken (under Protein > Chicken)
            'chicken' => [
                ['name' => 'Chicken Breast', 'alternatives' => ['chicken thighs', 'turkey breast']],
                ['name' => 'Chicken Thigh', 'alternatives' => ['chicken drumsticks', 'chicken leg quarters']],
                ['name' => 'Whole Chicken', 'alternatives' => ['chicken parts', 'cornish hen']],
                ['name' => 'Chicken Wings', 'alternatives' => ['chicken drumettes', 'chicken flats']],
                ['name' => 'Ground Chicken', 'alternatives' => ['ground turkey', 'ground pork']],
            ],

            // Pork (under Protein > Pork)
            'pork' => [
                ['name' => 'Pork Belly', 'alternatives' => ['bacon', 'pancetta']],
                ['name' => 'Ground Pork', 'alternatives' => ['ground beef', 'ground turkey']],
                ['name' => 'Pork Chops', 'alternatives' => ['pork tenderloin', 'pork shoulder']],
                ['name' => 'Pork Shoulder', 'alternatives' => ['pork butt', 'pork leg']],
                ['name' => 'Bacon', 'alternatives' => ['pancetta', 'ham']],
            ],

            // Seafood (under Protein > Seafood)
            'seafood' => [
                ['name' => 'Salmon', 'alternatives' => ['trout', 'cod', 'halibut']],
                ['name' => 'Shrimp', 'alternatives' => ['prawns', 'lobster', 'crab']],
                ['name' => 'Tilapia', 'alternatives' => ['cod', 'haddock', 'flounder']],
                ['name' => 'Bangus (Milkfish)', 'alternatives' => ['salmon', 'trout']],
                ['name' => 'Tuna', 'alternatives' => ['salmon', 'mackerel']],
            ],

            // Leafy Greens (under Vegetables > Leafy Greens)
            'leafy-greens' => [
                ['name' => 'Spinach', 'alternatives' => ['kale', 'arugula', 'swiss chard']],
                ['name' => 'Lettuce', 'alternatives' => ['cabbage', 'spinach']],
                ['name' => 'Kale', 'alternatives' => ['spinach', 'collard greens']],
                ['name' => 'Bok Choy', 'alternatives' => ['napa cabbage', 'spinach']],
                ['name' => 'Arugula', 'alternatives' => ['spinach', 'watercress']],
            ],

            // Root Vegetables (under Vegetables > Root Vegetables)
            'root-vegetables' => [
                ['name' => 'Potato', 'alternatives' => ['sweet potato', 'turnips', 'parsnips']],
                ['name' => 'Sweet Potato', 'alternatives' => ['potato', 'yam', 'butternut squash']],
                ['name' => 'Carrot', 'alternatives' => ['parsnips', 'sweet potato']],
                ['name' => 'Radish', 'alternatives' => ['turnips', 'daikon']],
                ['name' => 'Turnips', 'alternatives' => ['rutabaga', 'parsnips']],
            ],

            // Onions & Garlic (under Vegetables > Onions & Garlic)
            'onions-garlic' => [
                ['name' => 'Yellow Onion', 'alternatives' => ['white onion', 'red onion']],
                ['name' => 'Red Onion', 'alternatives' => ['yellow onion', 'shallots']],
                ['name' => 'Garlic', 'alternatives' => ['garlic powder', 'shallots']],
                ['name' => 'Shallots', 'alternatives' => ['green onions', 'red onion']],
                ['name' => 'Green Onions', 'alternatives' => ['chives', 'leeks']],
            ],

            // Rice (under Carbohydrates > Rice)
            'rice' => [
                ['name' => 'Jasmine Rice', 'alternatives' => ['basmati rice', 'white rice']],
                ['name' => 'Brown Rice', 'alternatives' => ['quinoa', 'wild rice']],
                ['name' => 'Basmati Rice', 'alternatives' => ['jasmine rice', 'long grain rice']],
                ['name' => 'Glutinous Rice', 'alternatives' => ['short grain rice', 'arborio rice']],
                ['name' => 'Rice Noodles', 'alternatives' => ['pasta', 'shirataki noodles']],
            ],

            // Pasta & Noodles (under Carbohydrates > Pasta & Noodles)
            'pasta-noodles' => [
                ['name' => 'Spaghetti', 'alternatives' => ['linguine', 'angel hair pasta']],
                ['name' => 'Penne', 'alternatives' => ['rigatoni', 'fusilli']],
                ['name' => 'Fettuccine', 'alternatives' => ['tagliatelle', 'pappardelle']],
                ['name' => 'Pancit Canton', 'alternatives' => ['lo mein noodles', 'chow mein noodles']],
                ['name' => 'Rice Vermicelli', 'alternatives' => ['thin rice noodles', 'angel hair pasta']],
            ],

            // Bread & Flour (under Carbohydrates > Bread & Flour)
            'bread-flour' => [
                ['name' => 'All-Purpose Flour', 'alternatives' => ['bread flour', 'cake flour']],
                ['name' => 'Bread', 'alternatives' => ['tortillas', 'pita bread']],
                ['name' => 'Panko Breadcrumbs', 'alternatives' => ['regular breadcrumbs', 'crushed crackers']],
                ['name' => 'Rice Flour', 'alternatives' => ['cornstarch', 'tapioca flour']],
                ['name' => 'Cornstarch', 'alternatives' => ['arrowroot powder', 'tapioca starch']],
            ],

            // Filipino Spices (under Spices & Seasonings > Filipino Spices)
            'filipino-spices' => [
                ['name' => 'Bagoong', 'alternatives' => ['fish sauce', 'anchovy paste']],
                ['name' => 'Patis (Fish Sauce)', 'alternatives' => ['soy sauce', 'salt']],
                ['name' => 'Soy Sauce', 'alternatives' => ['tamari', 'coconut aminos']],
                ['name' => 'Vinegar', 'alternatives' => ['white vinegar', 'apple cider vinegar']],
                ['name' => 'Bay Leaves', 'alternatives' => ['dried herbs', 'thyme']],
            ],
        ];

        $createdCount = 0;

        foreach ($ingredients as $categorySlug => $categoryIngredients) {
            $category = $leafCategories->get($categorySlug);

            if (! $category) {
                $this->command->warn(sprintf("Category '%s' not found, skipping ingredients...", $categorySlug));

                continue;
            }

            $this->command->info('Creating ingredients for: '.$category->getPath());

            foreach ($categoryIngredients as $ingredientData) {
                Ingredient::create([
                    'name' => $ingredientData['name'],
                    'category_id' => $category->id,
                    'slug' => Str::slug($ingredientData['name']),
                    'alternatives' => $ingredientData['alternatives'],
                ]);

                ++$createdCount;
            }
        }

        $this->command->info('Ingredient seeding completed!');
        $this->command->info(sprintf('Created %d ingredients across ', $createdCount).count($ingredients).' categories');

        // Show summary by category hierarchy
        $this->command->info("\nIngredients by Category:");
        foreach (IngredientCategory::roots()->with(['children.ingredients'])->get() as $root) {
            $this->command->info('• '.$root->name);
            foreach ($root->children as $child) {
                $count = $child->ingredients()->count();
                $this->command->info(sprintf('  └── %s (%s ingredients)', $child->name, $count));
            }
        }
    }
}
