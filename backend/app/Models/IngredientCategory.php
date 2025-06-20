<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
     * Scope to search categories by name.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    /**
     * Scope to get categories ordered by ingredient count.
     */
    public function scopePopular($query)
    {
        return $query->withCount('ingredients')
            ->orderBy('ingredients_count', 'desc');
    }

    /**
     * Scope to get categories ordered by name.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}
