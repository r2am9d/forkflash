<?php

namespace App\Models;

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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Equipment extends Model
{
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
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($equipment) {
            if (empty($equipment->slug)) {
                $equipment->slug = Str::slug($equipment->name);
            }
        });

        static::updating(function ($equipment) {
            if ($equipment->isDirty('name') && empty($equipment->slug)) {
                $equipment->slug = Str::slug($equipment->name);
            }
        });
    }

    /**
     * Get the recipes that use this equipment.
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_equipments');
    }

    /**
     * Scope to search equipment by name.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    /**
     * Get the most commonly used equipment (based on recipe count).
     */
    public function scopePopular($query)
    {
        return $query->withCount('recipes')
            ->orderBy('recipes_count', 'desc');
    }
}
