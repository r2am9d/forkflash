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
 * @property int $step_number
 * @property string $text
 * @property array<mixed>|null $ingredients
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
        'step_number',
        'text',
        'ingredients',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ingredients' => 'array',
        'step_number' => 'integer',
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
}
