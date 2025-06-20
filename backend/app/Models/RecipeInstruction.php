<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlids;
use Database\Factories\RecipeInstructionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $ulid
 * @property int $recipe_id
 * @property int $sort
 * @property string $text
 * @property array<int>|null $ingredient_ids
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property Recipe $recipe
 */
final class RecipeInstruction extends Model
{
    /** @use HasFactory<RecipeInstructionFactory> */
    use HasFactory;

    use HasUlids;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ulid',
        'recipe_id',
        'sort',
        'text',
        'ingredient_ids',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ingredient_ids' => 'array',
        'sort' => 'integer',
    ];

    /**
     * Get the recipe that owns the instruction.
     *
     * @return BelongsTo<Recipe, $this>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the ingredients referenced in this instruction.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ingredient>
     */
    public function getReferencedIngredients()
    {
        if (empty($this->ingredient_ids)) {
            return collect();
        }

        return $this->recipe->ingredients()->whereIn('ingredients.id', $this->ingredient_ids)->get();
    }

    /**
     * Get ingredient IDs referenced in this instruction.
     *
     * @return array<int>
     */
    public function getIngredientIds(): array
    {
        return $this->ingredient_ids ?? [];
    }

    /**
     * Check if this instruction references any ingredients.
     */
    public function hasIngredientReferences(): bool
    {
        return !empty($this->ingredient_ids);
    }

    /**
     * Check if this instruction references a specific ingredient.
     */
    public function referencesIngredient(int $ingredientId): bool
    {
        return in_array($ingredientId, $this->ingredient_ids ?? []);
    }
}
