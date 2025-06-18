<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NutrientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nutrients = [
            // Macronutrients
            [
                'name' => 'Calories',
                'slug' => 'calories',
                'unit' => 'kcal',
                'display_name' => 'Calories',
                'category' => 'macronutrient',
                'description' => 'Energy content of food',
                'daily_value' => 2000,
            ],
            [
                'name' => 'Protein',
                'slug' => 'protein',
                'unit' => 'g',
                'display_name' => 'Protein',
                'category' => 'macronutrient',
                'description' => 'Essential for muscle building and repair',
                'daily_value' => 50,
            ],
            [
                'name' => 'Total Carbohydrates',
                'slug' => 'total-carbohydrates',
                'unit' => 'g',
                'display_name' => 'Total Carbohydrates',
                'category' => 'macronutrient',
                'description' => 'Primary source of energy',
                'daily_value' => 300,
            ],
            [
                'name' => 'Dietary Fiber',
                'slug' => 'dietary-fiber',
                'unit' => 'g',
                'display_name' => 'Dietary Fiber',
                'category' => 'macronutrient',
                'description' => 'Important for digestive health',
                'daily_value' => 25,
            ],
            [
                'name' => 'Sugar',
                'slug' => 'sugar',
                'unit' => 'g',
                'display_name' => 'Sugar',
                'category' => 'macronutrient',
                'description' => 'Natural and added sugars',
                'daily_value' => null,
            ],
            [
                'name' => 'Total Fat',
                'slug' => 'total-fat',
                'unit' => 'g',
                'display_name' => 'Total Fat',
                'category' => 'macronutrient',
                'description' => 'Essential for hormone production and vitamin absorption',
                'daily_value' => 65,
            ],
            [
                'name' => 'Saturated Fat',
                'slug' => 'saturated-fat',
                'unit' => 'g',
                'display_name' => 'Saturated Fat',
                'category' => 'other',
                'description' => 'Should be limited in diet',
                'daily_value' => 20,
            ],

            // Minerals
            [
                'name' => 'Sodium',
                'slug' => 'sodium',
                'unit' => 'mg',
                'display_name' => 'Sodium',
                'category' => 'mineral',
                'description' => 'Important for fluid balance, limit intake',
                'daily_value' => 2300,
            ],
            [
                'name' => 'Calcium',
                'slug' => 'calcium',
                'unit' => 'mg',
                'display_name' => 'Calcium',
                'category' => 'mineral',
                'description' => 'Essential for bone and teeth health',
                'daily_value' => 1000,
            ],
            [
                'name' => 'Iron',
                'slug' => 'iron',
                'unit' => 'mg',
                'display_name' => 'Iron',
                'category' => 'mineral',
                'description' => 'Essential for oxygen transport in blood',
                'daily_value' => 18,
            ],
            [
                'name' => 'Potassium',
                'slug' => 'potassium',
                'unit' => 'mg',
                'display_name' => 'Potassium',
                'category' => 'mineral',
                'description' => 'Important for heart and muscle function',
                'daily_value' => 3500,
            ],

            // Vitamins
            [
                'name' => 'Vitamin A',
                'slug' => 'vitamin-a',
                'unit' => 'IU',
                'display_name' => 'Vitamin A',
                'category' => 'vitamin',
                'description' => 'Important for vision and immune system',
                'daily_value' => 5000,
            ],
            [
                'name' => 'Vitamin C',
                'slug' => 'vitamin-c',
                'unit' => 'mg',
                'display_name' => 'Vitamin C',
                'category' => 'vitamin',
                'description' => 'Antioxidant, important for immune system',
                'daily_value' => 60,
            ],
            [
                'name' => 'Vitamin D',
                'slug' => 'vitamin-d',
                'unit' => 'IU',
                'display_name' => 'Vitamin D',
                'category' => 'vitamin',
                'description' => 'Important for bone health and calcium absorption',
                'daily_value' => 400,
            ],

            // Other nutrients
            [
                'name' => 'Cholesterol',
                'slug' => 'cholesterol',
                'unit' => 'mg',
                'display_name' => 'Cholesterol',
                'category' => 'other',
                'description' => 'Dietary cholesterol, should be limited',
                'daily_value' => 300,
            ],
        ];

        foreach ($nutrients as $nutrient) {
            \App\Models\Nutrient::create($nutrient);
        }

        $this->command->info('Created ' . count($nutrients) . ' standard nutrients');
    }
}
