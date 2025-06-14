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

        // Then seed recipes with instructions and images
        $this->call([
            RecipeSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
