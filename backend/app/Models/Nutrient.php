<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\NutrientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $unit_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Unit $unit
 */
final class Nutrient extends Model
{
    /** @use HasFactory<NutrientFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'unit_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    /**
     * Get the unit for this nutrient.
     *
     * @return BelongsTo<Unit, $this>
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the recipes that have this nutrient.
     *
     * @return BelongsToMany<Recipe, $this>
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_nutrients')
            ->withPivot(['amount', 'percentage_dv'])
            ->withTimestamps();
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        self::creating(function (Nutrient $nutrient): void {
            if (empty($nutrient->slug)) {
                $nutrient->slug = Str::slug($nutrient->name);
            }
        });
    }
}
