<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Equipment Model
 * 
 * Represents cooking equipment and tools used in recipes.
 * 
 * @property int $id
 * @property string $name Equipment name (e.g., "Stand Mixer", "Cast Iron Skillet")
 * @property string $slug URL-friendly slug
 * @property string|null $category Equipment category (e.g., "baking", "stovetop", "small-appliance")
 * @property bool $is_essential Whether this equipment is considered essential for basic cooking
 * @property array|null $alternatives JSON array of alternative equipment names
 * @property string|null $description Equipment description and usage notes
 * @property float|null $average_price Average price in USD for budgeting features
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Equipment extends Model
{
    use HasFactory, SoftDeletes;

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
        'category',
        'is_essential',
        'alternatives',
        'description',
        'average_price',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_essential' => 'boolean',
        'alternatives' => 'array',
        'average_price' => 'decimal:2',
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
        return $this->belongsToMany(Recipe::class, 'recipe_equipments')
            ->withPivot(['is_required', 'notes', 'display_order'])
            ->withTimestamps()
            ->orderByPivot('display_order');
    }

    /**
     * Scope to filter by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter essential equipment only.
     */
    public function scopeEssential($query)
    {
        return $query->where('is_essential', true);
    }

    /**
     * Scope to filter non-essential equipment.
     */
    public function scopeNonEssential($query)
    {
        return $query->where('is_essential', false);
    }

    /**
     * Scope to search equipment by name.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'ILIKE', "%{$search}%")
            ->orWhere('description', 'ILIKE', "%{$search}%");
    }

    /**
     * Get formatted price for display.
     */
    public function getFormattedPriceAttribute(): ?string
    {
        if ($this->average_price === null) {
            return null;
        }

        return '$' . number_format($this->average_price, 2);
    }

    /**
     * Check if equipment has alternatives.
     */
    public function hasAlternatives(): bool
    {
        return !empty($this->alternatives);
    }

    /**
     * Get alternatives as a formatted string.
     */
    public function getAlternativesListAttribute(): string
    {
        if (!$this->hasAlternatives()) {
            return 'No alternatives available';
        }

        return implode(', ', $this->alternatives);
    }
}
