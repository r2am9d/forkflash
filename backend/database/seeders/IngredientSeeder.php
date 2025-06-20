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
        $this->command->info('Creating ingredient categories...');

        // Create ingredient categories first
        $categories = [
            [
                'name' => 'Vegetables',
                'slug' => 'vegetables',
            ],
            [
                'name' => 'Fruits',
                'slug' => 'fruits', 
            ],
            [
                'name' => 'Proteins',
                'slug' => 'proteins',
            ],
            [
                'name' => 'Dairy',
                'slug' => 'dairy',
            ],
            [
                'name' => 'Grains & Starches',
                'slug' => 'grains-starches',
            ],
            [
                'name' => 'Spices & Herbs',
                'slug' => 'spices-herbs',
            ],
            [
                'name' => 'Oils & Fats',
                'slug' => 'oils-fats',
            ],
            [
                'name' => 'Pantry Items',
                'slug' => 'pantry-items',
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $category = IngredientCategory::create($categoryData);
            $createdCategories[$category->slug] = $category;
        }

        $this->command->info('Creating ingredients...');

        // Create common ingredients
        $ingredients = [
            // Vegetables
            ['name' => 'Onion', 'category' => 'vegetables', 'alternatives' => ['shallots', 'leeks']],
            ['name' => 'Garlic', 'category' => 'vegetables', 'alternatives' => ['garlic powder', 'shallots']],
            ['name' => 'Tomato', 'category' => 'vegetables', 'alternatives' => ['canned tomatoes', 'tomato paste']],
            ['name' => 'Bell Pepper', 'category' => 'vegetables', 'alternatives' => ['poblano pepper', 'anaheim pepper']],
            ['name' => 'Carrot', 'category' => 'vegetables', 'alternatives' => ['parsnips', 'sweet potato']],
            ['name' => 'Celery', 'category' => 'vegetables', 'alternatives' => ['fennel', 'bok choy']],
            ['name' => 'Mushroom', 'category' => 'vegetables', 'alternatives' => ['portobello', 'shiitake']],
            ['name' => 'Spinach', 'category' => 'vegetables', 'alternatives' => ['kale', 'arugula']],
            ['name' => 'Broccoli', 'category' => 'vegetables', 'alternatives' => ['cauliflower', 'brussels sprouts']],
            ['name' => 'Potato', 'category' => 'vegetables', 'alternatives' => ['sweet potato', 'turnips']],

            // Proteins
            ['name' => 'Chicken Breast', 'category' => 'proteins', 'alternatives' => ['chicken thighs', 'turkey breast']],
            ['name' => 'Ground Beef', 'category' => 'proteins', 'alternatives' => ['ground turkey', 'ground pork']],
            ['name' => 'Salmon', 'category' => 'proteins', 'alternatives' => ['trout', 'cod']],
            ['name' => 'Eggs', 'category' => 'proteins', 'alternatives' => ['egg substitute', 'flax eggs']],
            ['name' => 'Tofu', 'category' => 'proteins', 'alternatives' => ['tempeh', 'seitan']],

            // Dairy
            ['name' => 'Butter', 'category' => 'dairy', 'alternatives' => ['margarine', 'coconut oil']],
            ['name' => 'Milk', 'category' => 'dairy', 'alternatives' => ['almond milk', 'oat milk']],
            ['name' => 'Cheddar Cheese', 'category' => 'dairy', 'alternatives' => ['colby cheese', 'monterey jack']],
            ['name' => 'Mozzarella', 'category' => 'dairy', 'alternatives' => ['provolone', 'fontina']],
            ['name' => 'Greek Yogurt', 'category' => 'dairy', 'alternatives' => ['sour cream', 'buttermilk']],

            // Grains & Starches
            ['name' => 'Rice', 'category' => 'grains-starches', 'alternatives' => ['quinoa', 'barley']],
            ['name' => 'Pasta', 'category' => 'grains-starches', 'alternatives' => ['zucchini noodles', 'rice noodles']],
            ['name' => 'Bread', 'category' => 'grains-starches', 'alternatives' => ['tortillas', 'pita bread']],
            ['name' => 'Flour', 'category' => 'grains-starches', 'alternatives' => ['almond flour', 'coconut flour']],
            ['name' => 'Quinoa', 'category' => 'grains-starches', 'alternatives' => ['bulgur', 'couscous']],

            // Spices & Herbs
            ['name' => 'Salt', 'category' => 'spices-herbs', 'alternatives' => ['sea salt', 'kosher salt']],
            ['name' => 'Black Pepper', 'category' => 'spices-herbs', 'alternatives' => ['white pepper', 'cayenne pepper']],
            ['name' => 'Basil', 'category' => 'spices-herbs', 'alternatives' => ['oregano', 'thyme']],
            ['name' => 'Oregano', 'category' => 'spices-herbs', 'alternatives' => ['marjoram', 'basil']],
            ['name' => 'Thyme', 'category' => 'spices-herbs', 'alternatives' => ['sage', 'rosemary']],
            ['name' => 'Paprika', 'category' => 'spices-herbs', 'alternatives' => ['chili powder', 'cayenne pepper']],

            // Oils & Fats
            ['name' => 'Olive Oil', 'category' => 'oils-fats', 'alternatives' => ['avocado oil', 'canola oil']],
            ['name' => 'Vegetable Oil', 'category' => 'oils-fats', 'alternatives' => ['canola oil', 'sunflower oil']],
            ['name' => 'Coconut Oil', 'category' => 'oils-fats', 'alternatives' => ['butter', 'ghee']],

            // Pantry Items
            ['name' => 'Soy Sauce', 'category' => 'pantry-items', 'alternatives' => ['tamari', 'coconut aminos']],
            ['name' => 'Balsamic Vinegar', 'category' => 'pantry-items', 'alternatives' => ['red wine vinegar', 'apple cider vinegar']],
            ['name' => 'Honey', 'category' => 'pantry-items', 'alternatives' => ['maple syrup', 'agave nectar']],
            ['name' => 'Lemon Juice', 'category' => 'pantry-items', 'alternatives' => ['lime juice', 'white wine vinegar']],
            ['name' => 'Canned Tomatoes', 'category' => 'pantry-items', 'alternatives' => ['fresh tomatoes', 'tomato sauce']],
        ];

        foreach ($ingredients as $ingredientData) {
            $category = $createdCategories[$ingredientData['category']];
            
            Ingredient::create([
                'name' => $ingredientData['name'],
                'category_id' => $category->id,
                'slug' => Str::slug($ingredientData['name']),
                'alternatives' => $ingredientData['alternatives'],
            ]);
        }

        $this->command->info('Ingredient seeding completed!');
        $this->command->info('Created:');
        $this->command->info('- ' . IngredientCategory::count() . ' ingredient categories');
        $this->command->info('- ' . Ingredient::count() . ' ingredients');
    }
}
