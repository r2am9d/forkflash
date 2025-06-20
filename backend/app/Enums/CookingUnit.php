<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Standard cooking units for recipe ingredients.
 * Provides consistency while allowing custom units.
 */
final class CookingUnit
{
    /**
     * Volume measurements
     */
    public const VOLUME = [
        // US Standard
        'cup', 'cups',
        'tablespoon', 'tablespoons', 'tbsp',
        'teaspoon', 'teaspoons', 'tsp',
        'fluid ounce', 'fluid ounces', 'fl oz',
        'pint', 'pints', 'pt',
        'quart', 'quarts', 'qt',
        'gallon', 'gallons', 'gal',

        // Metric
        'milliliter', 'milliliters', 'ml',
        'liter', 'liters', 'l',
    ];

    /**
     * Weight measurements
     */
    public const WEIGHT = [
        // US Standard
        'pound', 'pounds', 'lb', 'lbs',
        'ounce', 'ounces', 'oz',

        // Metric
        'gram', 'grams', 'g',
        'kilogram', 'kilograms', 'kg',
    ];

    /**
     * Count and size measurements
     */
    public const COUNT = [
        // Quantities
        'piece', 'pieces', 'pc',
        'slice', 'slices',
        'clove', 'cloves',
        'head', 'heads',
        'bunch', 'bunches',
        'bundle', 'bundles',
        'can', 'cans',
        'package', 'packages', 'pkg',
        'jar', 'jars',
        'bottle', 'bottles',
        'bag', 'bags',
        'box', 'boxes',

        // Sizes
        'small', 'medium', 'large', 'extra large',
        'sm', 'med', 'lg', 'xl',
    ];

    /**
     * Special cooking measurements
     */
    public const SPECIAL = [
        'to taste',
        'as needed',
        'pinch',
        'dash',
        'handful',
        'splash',
        'drizzle',
    ];

    /**
     * Get all standard units as a flat array
     *
     * @return array<mixed>
     */
    public static function all(): array
    {
        return array_merge(
            self::VOLUME,
            self::WEIGHT,
            self::COUNT,
            self::SPECIAL
        );
    }

    /**
     * Get units grouped by type
     *
     * @return array<mixed>
     */
    public static function grouped(): array
    {
        return [
            'volume' => self::VOLUME,
            'weight' => self::WEIGHT,
            'count' => self::COUNT,
            'special' => self::SPECIAL,
        ];
    }

    /**
     * Check if a unit is standardized
     */
    public static function isStandard(string $unit): bool
    {
        return in_array(mb_strtolower($unit), array_map('strtolower', self::all()), true);
    }

    /**
     * Get the unit category
     */
    public static function getCategory(string $unit): ?string
    {
        $unit = mb_strtolower($unit);

        if (in_array($unit, array_map('strtolower', self::VOLUME), true)) {
            return 'volume';
        }

        if (in_array($unit, array_map('strtolower', self::WEIGHT), true)) {
            return 'weight';
        }

        if (in_array($unit, array_map('strtolower', self::COUNT), true)) {
            return 'count';
        }

        if (in_array($unit, array_map('strtolower', self::SPECIAL), true)) {
            return 'special';
        }

        return null;
    }

    /**
     * Standardize unit format (normalize common variations)
     */
    public static function standardize(string $unit): string
    {
        $unit = mb_trim(mb_strtolower($unit));

        // Common standardizations
        $standardizations = [
            // Tablespoon variations
            'tbsp' => 'tablespoon',
            'tablespoons' => 'tablespoon',

            // Teaspoon variations
            'tsp' => 'teaspoon',
            'teaspoons' => 'teaspoon',

            // Cup variations
            'cups' => 'cup',

            // Weight variations
            'lb' => 'pound',
            'lbs' => 'pound',
            'pounds' => 'pound',
            'oz' => 'ounce',
            'ounces' => 'ounce',

            // Metric variations
            'grams' => 'gram',
            'kilograms' => 'kilogram',
            'kg' => 'kilogram',
            'milliliters' => 'milliliter',
            'liters' => 'liter',

            // Count variations
            'pieces' => 'piece',
            'slices' => 'slice',
            'cloves' => 'clove',
            'bunches' => 'bunch',
        ];

        return $standardizations[$unit] ?? $unit;
    }

    /**
     * Get common units for frontend autocomplete
     *
     * @return array<string>
     */
    public static function getCommonUnits(): array
    {
        return [
            'cup', 'tablespoon', 'teaspoon', 'pound', 'ounce', 'gram',
            'piece', 'clove', 'bunch', 'can', 'package', 'medium', 'large',
            'to taste', 'pinch', 'dash',
        ];
    }
}
