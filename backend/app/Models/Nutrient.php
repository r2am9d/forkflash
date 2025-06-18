<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $unit
 * @property string $display_name
 * @property string $category
 * @property string|null $description
 * @property float|null $daily_value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Nutrient extends Model
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
        'unit',
        'display_name',
        'category',
        'description',
        'daily_value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'daily_value' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Nutrient $nutrient) {
            if (empty($nutrient->slug)) {
                $nutrient->slug = Str::slug($nutrient->name);
            }
            if (empty($nutrient->display_name)) {
                $nutrient->display_name = $nutrient->name;
            }
        });
    }

    /**
     * Get the recipes that have this nutrient.
     *
     * @return BelongsToMany<Recipe, $this>
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_nutrient')
            ->withPivot(['amount', 'per_serving', 'source', 'confidence_level', 'sort_order', 'is_active', 'verified_at', 'notes'])
            ->withTimestamps();
    }

    /**
     * Scope to get nutrients by category.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get formatted daily value with unit.
     */
    public function getFormattedDailyValueAttribute(): ?string
    {
        if ($this->daily_value === null) {
            return null;
        }

        return "{$this->daily_value}{$this->unit}";
    }

    /**
     * Check if this is a macronutrient.
     */
    public function isMacronutrient(): bool
    {
        return $this->category === 'macronutrient';
    }

    /**
     * Check if this is a vitamin.
     */
    public function isVitamin(): bool
    {
        return $this->category === 'vitamin';
    }

    /**
     * Check if this is a mineral.
     */
    public function isMineral(): bool
    {
        return $this->category === 'mineral';
    }
}
