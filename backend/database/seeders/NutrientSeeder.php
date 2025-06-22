<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Nutrient;
use App\Models\Unit;
use Illuminate\Database\Seeder;

final class NutrientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get available units for nutrient references
        $units = Unit::all()->keyBy('name');
        if ($units->isEmpty()) {
            $this->command->error('No units found! Please run UnitSeeder first.');

            return;
        }

        $nutrients = [
            // Based on actual JSON nutrition data
            ['name' => 'Calories', 'slug' => 'calories', 'unit' => 'kcal'],
            ['name' => 'Carbohydrates', 'slug' => 'carbohydrates', 'unit' => 'g'],
            ['name' => 'Protein', 'slug' => 'protein', 'unit' => 'g'],
            ['name' => 'Fat', 'slug' => 'fat', 'unit' => 'g'],
            ['name' => 'Saturated Fat', 'slug' => 'saturated-fat', 'unit' => 'g'],
            ['name' => 'Polyunsaturated Fat', 'slug' => 'polyunsaturated-fat', 'unit' => 'g'],
            ['name' => 'Monounsaturated Fat', 'slug' => 'monounsaturated-fat', 'unit' => 'g'],
            ['name' => 'Trans Fat', 'slug' => 'trans-fat', 'unit' => 'g'],
            ['name' => 'Cholesterol', 'slug' => 'cholesterol', 'unit' => 'mg'],
            ['name' => 'Sodium', 'slug' => 'sodium', 'unit' => 'mg'],
            ['name' => 'Potassium', 'slug' => 'potassium', 'unit' => 'mg'],
            ['name' => 'Fiber', 'slug' => 'fiber', 'unit' => 'g'],
            ['name' => 'Sugar', 'slug' => 'sugar', 'unit' => 'g'],
            ['name' => 'Vitamin A', 'slug' => 'vitamin-a', 'unit' => 'IU'],
            ['name' => 'Vitamin C', 'slug' => 'vitamin-c', 'unit' => 'mg'],
            ['name' => 'Calcium', 'slug' => 'calcium', 'unit' => 'mg'],
            ['name' => 'Iron', 'slug' => 'iron', 'unit' => 'mg'],
        ];

        foreach ($nutrients as $nutrientData) {
            // Find the unit by name
            $unitName = $nutrientData['unit'];
            $unit = $units->get($unitName);

            if (! $unit) {
                $this->command->warn(sprintf("Unit '%s' not found for nutrient '%s'. Skipping...", $unitName, $nutrientData['name']));

                continue;
            }

            // Replace unit string with unit_id
            $nutrientData['unit_id'] = $unit->id;
            unset($nutrientData['unit']);

            Nutrient::create($nutrientData);
        }

        $this->command->info('Created '.count($nutrients).' nutrients');
    }
}
