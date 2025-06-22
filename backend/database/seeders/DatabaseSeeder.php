<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');

        // Seed users first (required for recipe foreign keys)
        $this->call([
            UserSeeder::class,
        ]);

        // Seed units before recipes (required for unit_id foreign keys)
        $this->call([
            UnitSeeder::class,
        ]);

        // Seed ingredient categories and ingredients (required for recipe ingredients)
        $this->call([
            IngredientCategorySeeder::class,
            IngredientSeeder::class,
        ]);

        // Seed nutrients for recipe nutrition tracking
        $this->call([
            NutrientSeeder::class,
        ]);

        // Finally seed recipes with all related data
        $this->call([
            RecipeSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
