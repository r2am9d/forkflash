<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

final class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding units...');

        $units = [
            // Volume measurements
            ['name' => 'cup', 'display_name' => 'Cup', 'unit_type' => 'volume', 'abbreviation' => 'c'],
            ['name' => 'cups', 'display_name' => 'Cups', 'unit_type' => 'volume', 'abbreviation' => 'c'],
            ['name' => 'tablespoon', 'display_name' => 'Tablespoon', 'unit_type' => 'volume', 'abbreviation' => 'tbsp'],
            ['name' => 'tablespoons', 'display_name' => 'Tablespoons', 'unit_type' => 'volume', 'abbreviation' => 'tbsp'],
            ['name' => 'tbsp', 'display_name' => 'Tbsp', 'unit_type' => 'volume', 'abbreviation' => 'tbsp'],
            ['name' => 'teaspoon', 'display_name' => 'Teaspoon', 'unit_type' => 'volume', 'abbreviation' => 'tsp'],
            ['name' => 'teaspoons', 'display_name' => 'Teaspoons', 'unit_type' => 'volume', 'abbreviation' => 'tsp'],
            ['name' => 'tsp', 'display_name' => 'Tsp', 'unit_type' => 'volume', 'abbreviation' => 'tsp'],
            ['name' => 'fluid ounce', 'display_name' => 'Fluid Ounce', 'unit_type' => 'volume', 'abbreviation' => 'fl oz'],
            ['name' => 'fluid ounces', 'display_name' => 'Fluid Ounces', 'unit_type' => 'volume', 'abbreviation' => 'fl oz'],
            ['name' => 'fl oz', 'display_name' => 'Fl Oz', 'unit_type' => 'volume', 'abbreviation' => 'fl oz'],
            ['name' => 'pint', 'display_name' => 'Pint', 'unit_type' => 'volume', 'abbreviation' => 'pt'],
            ['name' => 'pints', 'display_name' => 'Pints', 'unit_type' => 'volume', 'abbreviation' => 'pt'],
            ['name' => 'pt', 'display_name' => 'Pt', 'unit_type' => 'volume', 'abbreviation' => 'pt'],
            ['name' => 'quart', 'display_name' => 'Quart', 'unit_type' => 'volume', 'abbreviation' => 'qt'],
            ['name' => 'quarts', 'display_name' => 'Quarts', 'unit_type' => 'volume', 'abbreviation' => 'qt'],
            ['name' => 'qt', 'display_name' => 'Qt', 'unit_type' => 'volume', 'abbreviation' => 'qt'],
            ['name' => 'gallon', 'display_name' => 'Gallon', 'unit_type' => 'volume', 'abbreviation' => 'gal'],
            ['name' => 'gallons', 'display_name' => 'Gallons', 'unit_type' => 'volume', 'abbreviation' => 'gal'],
            ['name' => 'gal', 'display_name' => 'Gal', 'unit_type' => 'volume', 'abbreviation' => 'gal'],
            ['name' => 'milliliter', 'display_name' => 'Milliliter', 'unit_type' => 'volume', 'abbreviation' => 'ml'],
            ['name' => 'milliliters', 'display_name' => 'Milliliters', 'unit_type' => 'volume', 'abbreviation' => 'ml'],
            ['name' => 'ml', 'display_name' => 'mL', 'unit_type' => 'volume', 'abbreviation' => 'ml'],
            ['name' => 'liter', 'display_name' => 'Liter', 'unit_type' => 'volume', 'abbreviation' => 'l'],
            ['name' => 'liters', 'display_name' => 'Liters', 'unit_type' => 'volume', 'abbreviation' => 'l'],
            ['name' => 'l', 'display_name' => 'L', 'unit_type' => 'volume', 'abbreviation' => 'l'],

            // Weight measurements
            ['name' => 'pound', 'display_name' => 'Pound', 'unit_type' => 'weight', 'abbreviation' => 'lb'],
            ['name' => 'pounds', 'display_name' => 'Pounds', 'unit_type' => 'weight', 'abbreviation' => 'lb'],
            ['name' => 'lb', 'display_name' => 'Lb', 'unit_type' => 'weight', 'abbreviation' => 'lb'],
            ['name' => 'lbs', 'display_name' => 'Lbs', 'unit_type' => 'weight', 'abbreviation' => 'lb'],
            ['name' => 'ounce', 'display_name' => 'Ounce', 'unit_type' => 'weight', 'abbreviation' => 'oz'],
            ['name' => 'ounces', 'display_name' => 'Ounces', 'unit_type' => 'weight', 'abbreviation' => 'oz'],
            ['name' => 'oz', 'display_name' => 'Oz', 'unit_type' => 'weight', 'abbreviation' => 'oz'],
            ['name' => 'gram', 'display_name' => 'Gram', 'unit_type' => 'weight', 'abbreviation' => 'g'],
            ['name' => 'grams', 'display_name' => 'Grams', 'unit_type' => 'weight', 'abbreviation' => 'g'],
            ['name' => 'g', 'display_name' => 'g', 'unit_type' => 'weight', 'abbreviation' => 'g'],
            ['name' => 'kilogram', 'display_name' => 'Kilogram', 'unit_type' => 'weight', 'abbreviation' => 'kg'],
            ['name' => 'kilograms', 'display_name' => 'Kilograms', 'unit_type' => 'weight', 'abbreviation' => 'kg'],
            ['name' => 'kg', 'display_name' => 'kg', 'unit_type' => 'weight', 'abbreviation' => 'kg'],

            // Count measurements
            ['name' => 'piece', 'display_name' => 'Piece', 'unit_type' => 'count', 'abbreviation' => 'pc'],
            ['name' => 'pieces', 'display_name' => 'Pieces', 'unit_type' => 'count', 'abbreviation' => 'pc'],
            ['name' => 'pc', 'display_name' => 'Pc', 'unit_type' => 'count', 'abbreviation' => 'pc'],
            ['name' => 'slice', 'display_name' => 'Slice', 'unit_type' => 'count'],
            ['name' => 'slices', 'display_name' => 'Slices', 'unit_type' => 'count'],
            ['name' => 'clove', 'display_name' => 'Clove', 'unit_type' => 'count'],
            ['name' => 'cloves', 'display_name' => 'Cloves', 'unit_type' => 'count'],
            ['name' => 'head', 'display_name' => 'Head', 'unit_type' => 'count'],
            ['name' => 'heads', 'display_name' => 'Heads', 'unit_type' => 'count'],
            ['name' => 'bunch', 'display_name' => 'Bunch', 'unit_type' => 'count'],
            ['name' => 'bunches', 'display_name' => 'Bunches', 'unit_type' => 'count'],
            ['name' => 'bundle', 'display_name' => 'Bundle', 'unit_type' => 'count'],
            ['name' => 'bundles', 'display_name' => 'Bundles', 'unit_type' => 'count'],
            ['name' => 'can', 'display_name' => 'Can', 'unit_type' => 'count'],
            ['name' => 'cans', 'display_name' => 'Cans', 'unit_type' => 'count'],
            ['name' => 'package', 'display_name' => 'Package', 'unit_type' => 'count', 'abbreviation' => 'pkg'],
            ['name' => 'packages', 'display_name' => 'Packages', 'unit_type' => 'count', 'abbreviation' => 'pkg'],
            ['name' => 'pkg', 'display_name' => 'Pkg', 'unit_type' => 'count', 'abbreviation' => 'pkg'],
            ['name' => 'jar', 'display_name' => 'Jar', 'unit_type' => 'count'],
            ['name' => 'jars', 'display_name' => 'Jars', 'unit_type' => 'count'],
            ['name' => 'bottle', 'display_name' => 'Bottle', 'unit_type' => 'count'],
            ['name' => 'bottles', 'display_name' => 'Bottles', 'unit_type' => 'count'],
            ['name' => 'bag', 'display_name' => 'Bag', 'unit_type' => 'count'],
            ['name' => 'bags', 'display_name' => 'Bags', 'unit_type' => 'count'],
            ['name' => 'box', 'display_name' => 'Box', 'unit_type' => 'count'],
            ['name' => 'boxes', 'display_name' => 'Boxes', 'unit_type' => 'count'],

            // Size measurements
            ['name' => 'small', 'display_name' => 'Small', 'unit_type' => 'size', 'abbreviation' => 'sm'],
            ['name' => 'medium', 'display_name' => 'Medium', 'unit_type' => 'size', 'abbreviation' => 'med'],
            ['name' => 'large', 'display_name' => 'Large', 'unit_type' => 'size', 'abbreviation' => 'lg'],
            ['name' => 'extra large', 'display_name' => 'Extra Large', 'unit_type' => 'size', 'abbreviation' => 'xl'],
            ['name' => 'small', 'display_name' => 'Small', 'unit_type' => 'size', 'abbreviation' => 'sm'],
            ['name' => 'medium', 'display_name' => 'Medium', 'unit_type' => 'size', 'abbreviation' => 'med'],
            ['name' => 'large', 'display_name' => 'Large', 'unit_type' => 'size', 'abbreviation' => 'lg'],
            ['name' => 'extra large', 'display_name' => 'Extra Large', 'unit_type' => 'size', 'abbreviation' => 'xl'],
            ['name' => 'sm', 'display_name' => 'Sm', 'unit_type' => 'size', 'abbreviation' => 'sm'],
            ['name' => 'med', 'display_name' => 'Med', 'unit_type' => 'size', 'abbreviation' => 'med'],
            ['name' => 'lg', 'display_name' => 'Lg', 'unit_type' => 'size', 'abbreviation' => 'lg'],
            ['name' => 'xl', 'display_name' => 'XL', 'unit_type' => 'size', 'abbreviation' => 'xl'],

            // Special measurements
            ['name' => 'to taste', 'display_name' => 'To Taste', 'unit_type' => 'special'],
            ['name' => 'as needed', 'display_name' => 'As Needed', 'unit_type' => 'special'],
            ['name' => 'pinch', 'display_name' => 'Pinch', 'unit_type' => 'special'],
            ['name' => 'dash', 'display_name' => 'Dash', 'unit_type' => 'special'],
            ['name' => 'handful', 'display_name' => 'Handful', 'unit_type' => 'special'],
            ['name' => 'splash', 'display_name' => 'Splash', 'unit_type' => 'special'],
            ['name' => 'drizzle', 'display_name' => 'Drizzle', 'unit_type' => 'special'],
        ];

        foreach ($units as $unitData) {
            Unit::firstOrCreate(
                ['name' => $unitData['name']],
                array_merge($unitData, [
                    'is_standardized' => true,
                ])
            );
        }

        $this->command->info('Created '.Unit::count().' units');
    }
}
