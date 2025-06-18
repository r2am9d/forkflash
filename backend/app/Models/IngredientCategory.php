<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $parent_id
 * @property int $sort_order
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read IngredientCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, IngredientCategory> $children
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ingredient> $ingredients
 */
final class IngredientCategory extends Model
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
        'description',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent category.
     *
     * @return BelongsTo<IngredientCategory, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(IngredientCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     *
     * @return HasMany<IngredientCategory, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(IngredientCategory::class, 'parent_id')->orderBy('sort_order');
    }

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
     * Scope to get only root categories (no parent).
     *
     * @param \Illuminate\Database\Eloquent\Builder<IngredientCategory> $query
     * @return \Illuminate\Database\Eloquent\Builder<IngredientCategory>
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id')->orderBy('sort_order');
    }

    /**
     * Scope to get only active categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder<IngredientCategory> $query
     * @return \Illuminate\Database\Eloquent\Builder<IngredientCategory>
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
