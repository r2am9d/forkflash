<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $category_id
 * @property array<mixed>|null $alternatives
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read IngredientCategory $category
 * @property-read Collection<int, Recipe> $recipes
 */
final class Ingredient extends Model
{
    /** @use HasFactory<IngredientFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'alternatives',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'alternatives' => 'array',
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
            ->withPivot(['display_text', 'sort'])
            ->orderByPivot('sort');
    }

    /**
     * Scope to search ingredients by name.
     *
     * @param  mixed  $query
     * @param  string  $term
     */
    public function scopeSearch($query, $term): mixed
    {
        return $query->where('name', 'like', sprintf('%%%s%%', $term));
    }

    /**
     * Get the most commonly used ingredients (based on recipe count).
     *
     * @param  mixed  $query
     */
    public function scopePopular($query): mixed
    {
        return $query->withCount('recipes')
            ->orderBy('recipes_count', 'desc');
    }
}
