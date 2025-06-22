<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\IngredientCategoryFactory;
use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Hierarchical Ingredient Category Model
 *
 * Supports parent-child relationships for better mobile UI organization.
 * Example: Protein -> Beef -> Ground Beef (ingredient)
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read IngredientCategory|null $parent
 * @property-read Collection<int, IngredientCategory> $children
 * @property-read Collection<int, Ingredient> $ingredients
 */
final class IngredientCategory extends Model
{
    /** @use HasFactory<IngredientCategoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // No custom casts needed
    ];

    /**
     * Get the ingredients in this category.
     *
     * @return HasMany<Ingredient, $this>
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class, 'category_id');
    }

    /**
     * Get the parent category.
     *
     * @return BelongsTo<IngredientCategory, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the child categories.
     *
     * @return HasMany<IngredientCategory, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get all descendants (children, grandchildren, etc.) recursively.
     *
     * @return HasMany<IngredientCategory, $this>
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ingredients in this category and all subcategories.
     */
    public function allIngredients(): mixed
    {
        // Get ingredients directly in this category
        $ingredientIds = $this->ingredients()->pluck('id')->toArray();

        // Get ingredients from all child categories recursively
        foreach ($this->children as $child) {
            $childIngredientIds = $child->allIngredients()->pluck('id')->toArray();
            $ingredientIds = array_merge($ingredientIds, $childIngredientIds);
        }

        // Return a query builder for the unique ingredient IDs
        return Ingredient::whereIn('id', array_unique($ingredientIds));
    }

    /**
     * Check if this is a root category (no parent).
     */
    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if this is a leaf category (no children).
     */
    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }

    /**
     * Get the depth level of this category (0 = root, 1 = first level, etc.).
     */
    public function getDepth(): int
    {
        if ($this->isRoot()) {
            return 0;
        }

        return 1 + $this->parent->getDepth();
    }

    /**
     * Get the full path from root to this category.
     * Example: "Protein > Beef" or "Vegetables > Root Vegetables"
     */
    public function getPath(string $separator = ' > '): string
    {
        if ($this->isRoot()) {
            return $this->name;
        }

        return $this->parent->getPath($separator).$separator.$this->name;
    }

    /**
     * Scope to get only root categories (no parent).
     *
     * @param  mixed  $query
     */
    public function scopeRoots($query): mixed
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get only leaf categories (no children).
     *
     * @param  mixed  $query
     */
    public function scopeLeaves($query): mixed
    {
        return $query->whereDoesntHave('children');
    }

    /**
     * Scope to get categories at a specific depth level.
     *
     * @param  mixed  $query
     */
    public function scopeAtDepth($query, int $depth): mixed
    {
        if ($depth === 0) {
            return $query->whereNull('parent_id');
        }

        // For deeper levels, we'd need a more complex query
        // This is a simplified version
        return $query->whereHas('parent', function ($q) use ($depth): void {
            if ($depth === 1) {
                $q->whereNull('parent_id');
            }
        });
    }

    /**
     * Scope to get categories with their children loaded.
     *
     * @param  mixed  $query
     */
    public function scopeWithHierarchy($query): mixed
    {
        return $query->with(['children', 'parent']);
    }

    /**
     * Scope to search categories by name.
     *
     * @param  mixed  $query
     * @param  string  $term
     */
    public function scopeSearch($query, $term): mixed
    {
        return $query->where('name', 'like', sprintf('%%%s%%', $term));
    }

    /**
     * Scope to get categories ordered by ingredient count.
     *
     * @param  mixed  $query
     */
    public function scopePopular($query): mixed
    {
        return $query->withCount('ingredients')
            ->orderBy('ingredients_count', 'desc');
    }

    /**
     * Scope to get categories ordered by name.
     *
     * @param  mixed  $query
     */
    public function scopeOrdered($query): mixed
    {
        return $query->orderBy('name');
    }
}
