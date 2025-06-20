<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * Tag Model
 * 
 * Simple tag system for recipes - optimized for mobile performance.
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $color
 * @property int $usage_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Tag extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
        'usage_count',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'usage_count' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Get the recipes that belong to this tag.
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_tags');
    }

    /**
     * Scope a query to search tags by name.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    /**
     * Scope a query to order by popularity (usage count).
     */
    public function scopePopular($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }

    /**
     * Increment the usage count when tag is used.
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement the usage count when tag is removed.
     */
    public function decrementUsage()
    {
        if ($this->usage_count > 0) {
            $this->decrement('usage_count');
        }
    }
}
