<?php

declare(strict_types=1);

namespace App\Models;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Recipe> $recipes
 */
final class Ingredient extends Model
{
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
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    /**
     * Get the most commonly used ingredients (based on recipe count).
     */
    public function scopePopular($query)
    {
        return $query->withCount('recipes')
            ->orderBy('recipes_count', 'desc');
    }
}
