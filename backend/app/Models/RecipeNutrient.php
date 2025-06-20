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
 * @property float|null $percentage_dv
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
    protected $table = 'recipe_nutrients';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'recipe_id',
        'nutrient_id',
        'amount',
        'percentage_dv',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:4',
        'percentage_dv' => 'decimal:2',
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
     * Get formatted amount with unit.
     */
    public function getFormattedAmountAttribute(): string
    {
        $unit = $this->nutrient?->unit?->name ?? '';
        return "{$this->amount}{$unit}";
    }

    /**
     * Get formatted percentage daily value.
     */
    public function getFormattedPercentageDvAttribute(): ?string
    {
        if ($this->percentage_dv === null) {
            return null;
        }

        return "({$this->percentage_dv}%)";
    }
}
