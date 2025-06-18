<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $recipe_id
 * @property int $nutrient_id
 * @property float $amount
 * @property bool $per_serving
 * @property string|null $source
 * @property string $confidence_level
 * @property int $sort_order
 * @property bool $is_active
 * @property Carbon|null $verified_at
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Recipe $recipe
 * @property-read Nutrient $nutrient
 */
final class RecipeNutrient extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'recipe_nutrient';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'recipe_id',
        'nutrient_id',
        'amount',
        'per_serving',
        'source',
        'confidence_level',
        'sort_order',
        'is_active',
        'verified_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:4',
        'per_serving' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the recipe that owns this nutrient data.
     *
     * @return BelongsTo<Recipe, $this>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the nutrient definition.
     *
     * @return BelongsTo<Nutrient, $this>
     */
    public function nutrient(): BelongsTo
    {
        return $this->belongsTo(Nutrient::class);
    }

    /**
     * Scope to get only active nutrient data.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get nutrients by confidence level.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     * @param string $level
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeByConfidence($query, string $level)
    {
        return $query->where('confidence_level', $level);
    }

    /**
     * Scope to order by display order.
     *
     * @param \Illuminate\Database\Eloquent\Builder<static> $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get formatted amount with unit from nutrient.
     */
    public function getFormattedAmountAttribute(): string
    {
        return "{$this->amount}{$this->nutrient->unit}";
    }

    /**
     * Check if this nutrient data is verified.
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Mark this nutrient data as verified.
     */
    public function markAsVerified(): void
    {
        $this->update(['verified_at' => now()]);
    }
}
