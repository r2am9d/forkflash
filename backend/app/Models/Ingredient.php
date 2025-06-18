<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $category_id
 * @property array<mixed>|null $common_substitutes
 * @property array<mixed>|null $storage_info
 * @property array<mixed>|null $dietary_flags
 * @property float|null $average_price
 * @property string|null $price_unit
 * @property array<mixed>|null $seasonality
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read IngredientCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Recipe> $recipes
 */
final class Ingredient extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'common_substitutes',
        'storage_info',
        'dietary_flags',
        'average_price',
        'price_unit',
        'seasonality',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'common_substitutes' => 'array',
        'storage_info' => 'array',
        'dietary_flags' => 'array',
        'average_price' => 'decimal:2',
        'seasonality' => 'array',
    ];

    /**
     * Get the category this ingredient belongs to.
     *
     * @return BelongsTo<IngredientCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(IngredientCategory::class);
    }

    /**
     * Get the recipes that use this ingredient.
     *
     * @return BelongsToMany<Recipe, $this>
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients')
            ->withPivot(['quantity', 'unit_id', 'preparation_notes', 'is_optional', 'display_order'])
            ->withTimestamps();
    }

    /**
     * Scope to filter by dietary flags.
     *
     * @param \Illuminate\Database\Eloquent\Builder<Ingredient> $query
     * @param string $flag
     * @return \Illuminate\Database\Eloquent\Builder<Ingredient>
     */
    public function scopeWithDietaryFlag($query, string $flag)
    {
        return $query->whereJsonContains('dietary_flags->' . $flag, true);
    }

    /**
     * Scope to filter vegetarian ingredients.
     *
     * @param \Illuminate\Database\Eloquent\Builder<Ingredient> $query
     * @return \Illuminate\Database\Eloquent\Builder<Ingredient>
     */
    public function scopeVegetarian($query)
    {
        return $query->withDietaryFlag('vegetarian');
    }

    /**
     * Scope to filter vegan ingredients.
     *
     * @param \Illuminate\Database\Eloquent\Builder<Ingredient> $query
     * @return \Illuminate\Database\Eloquent\Builder<Ingredient>
     */
    public function scopeVegan($query)
    {
        return $query->withDietaryFlag('vegan');
    }

    /**
     * Scope to filter gluten-free ingredients.
     *
     * @param \Illuminate\Database\Eloquent\Builder<Ingredient> $query
     * @return \Illuminate\Database\Eloquent\Builder<Ingredient>
     */
    public function scopeGlutenFree($query)
    {
        return $query->withDietaryFlag('gluten_free');
    }

    /**
     * Check if ingredient has a specific dietary flag.
     */
    public function hasDietaryFlag(string $flag): bool
    {
        return $this->dietary_flags && ($this->dietary_flags[$flag] ?? false);
    }

    /**
     * Get human-readable dietary flags.
     *
     * @return array<string>
     */
    public function getDietaryLabelsAttribute(): array
    {
        if (!$this->dietary_flags) {
            return [];
        }

        $labels = [];
        if ($this->dietary_flags['vegetarian'] ?? false) $labels[] = 'Vegetarian';
        if ($this->dietary_flags['vegan'] ?? false) $labels[] = 'Vegan';
        if ($this->dietary_flags['gluten_free'] ?? false) $labels[] = 'Gluten-Free';
        if ($this->dietary_flags['dairy_free'] ?? false) $labels[] = 'Dairy-Free';
        if ($this->dietary_flags['nut_free'] ?? false) $labels[] = 'Nut-Free';

        return $labels;
    }
}
