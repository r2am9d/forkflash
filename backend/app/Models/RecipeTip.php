<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Recipe Tip Model
 * 
 * Represents individual cooking tips for recipes with categorization, community scoring,
 * and usage tracking. Supports different tip categories: cooking, prep, substitution, storage.
 * 
 * @property int $id
 * @property int $recipe_id
 * @property string $tip_text
 * @property string $tip_category
 * @property int $sort
 * @property bool $is_public
 * @property int|null $created_by_user_id
 * @property int $helpfulness_score
 * @property int $times_used
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Recipe $recipe
 * @property-read User|null $createdBy
 */
class RecipeTip extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'recipe_id',
        'tip_text',
        'tip_category',
        'sort',
        'is_public',
        'created_by_user_id',
        'helpfulness_score',
        'times_used',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_public' => 'boolean',
        'sort' => 'integer',
        'helpfulness_score' => 'integer',
        'times_used' => 'integer',
    ];

    /**
     * Available tip categories.
     */
    public const TIP_CATEGORIES = [
        'cooking' => 'Cooking Technique',
        'prep' => 'Preparation Tips',
        'substitution' => 'Ingredient Substitutions',
        'storage' => 'Storage & Leftovers',
    ];

    /**
     * Get the recipe that owns this tip.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the user who created this tip.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Scope to get tips by category.
     */
    public function scopeOfCategory($query, string $category)
    {
        return $query->where('tip_category', $category);
    }

    /**
     * Scope to get public tips only.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('created_at');
    }

    /**
     * Scope to order by helpfulness score.
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpfulness_score', 'desc');
    }

    /**
     * Scope to order by usage frequency.
     */
    public function scopeMostUsed($query)
    {
        return $query->orderBy('times_used', 'desc');
    }

    /**
     * Get formatted tip category label.
     */
    public function getFormattedCategoryAttribute(): string
    {
        return self::TIP_CATEGORIES[$this->tip_category] ?? ucfirst($this->tip_category);
    }

    /**
     * Check if tip is of a specific category.
     */
    public function isCategory(string $category): bool
    {
        return $this->tip_category === $category;
    }

    /**
     * Increment helpfulness score.
     */
    public function incrementHelpfulness(): void
    {
        $this->increment('helpfulness_score');
    }

    /**
     * Increment times used counter.
     */
    public function incrementUsage(): void
    {
        $this->increment('times_used');
    }
}
