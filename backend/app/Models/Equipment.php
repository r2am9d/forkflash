<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\EquipmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * Equipment Model
 *
 * Represents cooking equipment and tools used in recipes.
 *
 * @property int $id
 * @property string $name Equipment name (e.g., "Air fryer", "Grill")
 * @property string $slug URL-friendly slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Equipment extends Model
{
    /** @use HasFactory<EquipmentFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'equipments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        //
    ];

    /**
     * Get the recipes that use this equipment.
     *
     * @return BelongsToMany<Recipe, $this>
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_equipments');
    }

    /**
     * Scope to search equipment by name.
     *
     * @param  mixed  $query
     * @param  string  $term
     */
    public function scopeSearch($query, $term): mixed
    {
        return $query->where('name', 'like', sprintf('%%%s%%', $term));
    }

    /**
     * Get the most commonly used equipment (based on recipe count).
     *
     * @param  mixed  $query
     */
    public function scopePopular($query): mixed
    {
        return $query->withCount('recipes')
            ->orderBy('recipes_count', 'desc');
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($equipment): void {
            if (empty($equipment->slug)) {
                $equipment->slug = Str::slug($equipment->name);
            }
        });

        self::updating(function ($equipment): void {
            if ($equipment->isDirty('name') && empty($equipment->slug)) {
                $equipment->slug = Str::slug($equipment->name);
            }
        });
    }
}
