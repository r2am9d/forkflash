<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\IngredientCategory;
use Illuminate\Database\Seeder;

final class IngredientCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating hierarchical ingredient categories...');

        // Root categories
        $protein = IngredientCategory::create([
            'name' => 'Protein',
            'slug' => 'protein',
        ]);

        $vegetables = IngredientCategory::create([
            'name' => 'Vegetables',
            'slug' => 'vegetables',
        ]);

        $carbohydrates = IngredientCategory::create([
            'name' => 'Carbohydrates',
            'slug' => 'carbohydrates',
        ]);

        IngredientCategory::create([
            'name' => 'Dairy',
            'slug' => 'dairy',
        ]);

        $spices = IngredientCategory::create([
            'name' => 'Spices & Seasonings',
            'slug' => 'spices-seasonings',
        ]);

        // Protein subcategories
        IngredientCategory::create([
            'name' => 'Beef',
            'slug' => 'beef',
            'parent_id' => $protein->id,
        ]);

        IngredientCategory::create([
            'name' => 'Chicken',
            'slug' => 'chicken',
            'parent_id' => $protein->id,
        ]);

        IngredientCategory::create([
            'name' => 'Pork',
            'slug' => 'pork',
            'parent_id' => $protein->id,
        ]);

        IngredientCategory::create([
            'name' => 'Seafood',
            'slug' => 'seafood',
            'parent_id' => $protein->id,
        ]);

        // Vegetable subcategories
        IngredientCategory::create([
            'name' => 'Leafy Greens',
            'slug' => 'leafy-greens',
            'parent_id' => $vegetables->id,
        ]);

        IngredientCategory::create([
            'name' => 'Root Vegetables',
            'slug' => 'root-vegetables',
            'parent_id' => $vegetables->id,
        ]);

        IngredientCategory::create([
            'name' => 'Onions & Garlic',
            'slug' => 'onions-garlic',
            'parent_id' => $vegetables->id,
        ]);

        // Carbohydrate subcategories
        IngredientCategory::create([
            'name' => 'Rice',
            'slug' => 'rice',
            'parent_id' => $carbohydrates->id,
        ]);

        IngredientCategory::create([
            'name' => 'Pasta & Noodles',
            'slug' => 'pasta-noodles',
            'parent_id' => $carbohydrates->id,
        ]);

        IngredientCategory::create([
            'name' => 'Bread & Flour',
            'slug' => 'bread-flour',
            'parent_id' => $carbohydrates->id,
        ]);

        // Filipino-specific subcategories
        IngredientCategory::create([
            'name' => 'Filipino Spices',
            'slug' => 'filipino-spices',
            'parent_id' => $spices->id,
        ]);

        $this->command->info('Hierarchical ingredient categories created successfully!');
        $this->command->info('Root categories: '.IngredientCategory::roots()->count());
        $this->command->info('Total categories: '.IngredientCategory::count());

        // Show the hierarchy
        $this->command->info("\nCategory Hierarchy:");
        foreach (IngredientCategory::roots()->with('children')->get() as $root) {
            $this->command->info('• '.$root->name);
            foreach ($root->children as $child) {
                $this->command->info('  └── '.$child->name);
            }
        }
    }
}
