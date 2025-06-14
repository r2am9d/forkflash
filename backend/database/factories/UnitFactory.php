<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CookingUnit;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
final class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pick a random unit from our standardized cooking units
        $allUnits = CookingUnit::all();
        $unit = $this->faker->randomElement($allUnits);
        $unitType = CookingUnit::getCategory($unit) ?? 'other';

        return [
            'name' => mb_strtolower((string) $unit),
            'display_name' => ucfirst((string) $unit),
            'unit_type' => $unitType,
            'is_standardized' => true,
            'conversion_factor' => null, // Will be added in future phases
            'abbreviation' => $this->getAbbreviation($unit),
            'description' => $this->getDescription($unit, $unitType),
        ];
    }

    /**
     * Create a standardized volume unit.
     */
    public function volume(): static
    {
        return $this->state(function (array $attributes): array {
            $volumeUnits = CookingUnit::VOLUME;
            $unit = $this->faker->randomElement($volumeUnits);

            return [
                'name' => mb_strtolower($unit),
                'display_name' => ucfirst($unit),
                'unit_type' => 'volume',
                'is_standardized' => true,
                'abbreviation' => $this->getAbbreviation($unit),
                'description' => $this->getDescription($unit, 'volume'),
            ];
        });
    }

    /**
     * Create a standardized weight unit.
     */
    public function weight(): static
    {
        return $this->state(function (array $attributes): array {
            $weightUnits = CookingUnit::WEIGHT;
            $unit = $this->faker->randomElement($weightUnits);

            return [
                'name' => mb_strtolower($unit),
                'display_name' => ucfirst($unit),
                'unit_type' => 'weight',
                'is_standardized' => true,
                'abbreviation' => $this->getAbbreviation($unit),
                'description' => $this->getDescription($unit, 'weight'),
            ];
        });
    }

    /**
     * Create a standardized count unit.
     */
    public function countUnit(): static
    {
        return $this->state(function (array $attributes): array {
            $countUnits = CookingUnit::COUNT;
            $unit = $this->faker->randomElement($countUnits);

            return [
                'name' => mb_strtolower($unit),
                'display_name' => ucfirst($unit),
                'unit_type' => 'count',
                'is_standardized' => true,
                'abbreviation' => $this->getAbbreviation($unit),
                'description' => $this->getDescription($unit, 'count'),
            ];
        });
    }

    /**
     * Create a custom (non-standardized) unit.
     */
    public function custom(): static
    {
        return $this->state(function (array $attributes): array {
            $customUnits = [
                'container', 'serving', 'portion', 'handful', 'square',
                'stick', 'strip', 'wedge', 'ring', 'sheet',
            ];
            $unit = $this->faker->randomElement($customUnits);

            return [
                'name' => mb_strtolower($unit),
                'display_name' => ucfirst($unit),
                'unit_type' => 'other',
                'is_standardized' => false,
                'abbreviation' => null,
                'description' => 'Custom unit: '.$unit,
            ];
        });
    }

    /**
     * Create a specific unit by name.
     */
    public function specific(string $unitName, ?string $unitType = null): static
    {
        return $this->state(function (array $attributes) use ($unitName, $unitType): array {
            $type = $unitType ?? CookingUnit::getCategory($unitName) ?? 'other';
            $isStandardized = CookingUnit::isStandard($unitName);

            return [
                'name' => mb_strtolower($unitName),
                'display_name' => ucfirst($unitName),
                'unit_type' => $type,
                'is_standardized' => $isStandardized,
                'abbreviation' => $this->getAbbreviation($unitName),
                'description' => $this->getDescription($unitName, $type),
            ];
        });
    }

    /**
     * Get abbreviation for a unit.
     */
    private function getAbbreviation(string $unit): ?string
    {
        $abbreviations = [
            'tablespoon' => 'tbsp',
            'tablespoons' => 'tbsp',
            'teaspoon' => 'tsp',
            'teaspoons' => 'tsp',
            'fluid ounce' => 'fl oz',
            'fluid ounces' => 'fl oz',
            'pound' => 'lb',
            'pounds' => 'lb',
            'ounce' => 'oz',
            'ounces' => 'oz',
            'gram' => 'g',
            'grams' => 'g',
            'kilogram' => 'kg',
            'kilograms' => 'kg',
            'milliliter' => 'ml',
            'milliliters' => 'ml',
            'liter' => 'l',
            'liters' => 'l',
            'package' => 'pkg',
            'packages' => 'pkg',
            'small' => 'sm',
            'medium' => 'med',
            'large' => 'lg',
            'extra large' => 'xl',
        ];

        return $abbreviations[mb_strtolower($unit)] ?? null;
    }

    /**
     * Get description for a unit.
     */
    private function getDescription(string $unit, string $type): string
    {
        $descriptions = [
            // Volume
            'cup' => 'Standard US cooking cup (8 fl oz)',
            'tablespoon' => 'Standard cooking tablespoon (3 tsp)',
            'teaspoon' => 'Standard cooking teaspoon',
            'gallon' => 'US liquid gallon',
            'liter' => 'Metric liter',
            'milliliter' => 'Metric milliliter',

            // Weight
            'pound' => 'Imperial pound (16 oz)',
            'ounce' => 'Imperial ounce',
            'gram' => 'Metric gram',
            'kilogram' => 'Metric kilogram (1000g)',

            // Count
            'piece' => 'Individual item or portion',
            'clove' => 'Single clove (typically garlic)',
            'head' => 'Whole head (lettuce, garlic, etc.)',
            'bunch' => 'Bundled group (herbs, vegetables)',
            'can' => 'Standard canned portion',
            'package' => 'Pre-packaged portion',
            'medium' => 'Medium-sized portion',
            'large' => 'Large-sized portion',

            // Special
            'to taste' => 'Add according to preference',
            'pinch' => 'Small amount between thumb and forefinger',
            'dash' => 'Quick shake or small splash',
        ];

        return $descriptions[mb_strtolower($unit)] ?? sprintf('Standard %s measurement unit', $type);
    }
}
