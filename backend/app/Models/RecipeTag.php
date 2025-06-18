<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RecipeTag extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'color',
        'is_dietary_restriction',
        'is_featured',
        'usage_count',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_dietary_restriction' => 'boolean',
        'is_featured' => 'boolean',
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
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_recipe_tags');
    }

    /**
     * Scope a query to only include tags of a given category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include dietary restriction tags.
     */
    public function scopeDietaryRestrictions($query)
    {
        return $query->where('is_dietary_restriction', true);
    }

    /**
     * Scope a query to only include featured tags.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order by popularity (usage count).
     */
    public function scopePopular($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }

    /**
     * Scope a query to search tags by name or description.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('name', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Get the formatted color attribute.
     */
    public function getFormattedColorAttribute()
    {
        return $this->color ?: '#6B7280';
    }

    /**
     * Check if this tag is a dietary restriction.
     */
    public function isDietaryRestriction()
    {
        return $this->is_dietary_restriction;
    }

    /**
     * Check if this tag is featured.
     */
    public function isFeatured()
    {
        return $this->is_featured;
    }

    /**
     * Increment the usage count.
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement the usage count.
     */
    public function decrementUsage()
    {
        $this->decrement('usage_count');
    }

    /**
     * Get common tag categories.
     */
    public static function getCategories()
    {
        return [
            'dietary' => 'Dietary Restrictions',
            'cooking-method' => 'Cooking Methods',
            'flavor' => 'Flavor Profiles',
            'occasion' => 'Occasions',
            'course' => 'Course Types',
            'difficulty' => 'Difficulty Levels',
            'season' => 'Seasonal',
            'health' => 'Health & Nutrition',
            'style' => 'Cooking Styles',
            'time' => 'Time-based',
        ];
    }
}
