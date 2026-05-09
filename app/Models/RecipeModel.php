<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeModel extends Model
{
    protected $table = 'recipes';
    public $timestamps = false;

    protected $fillable = ['itemName', 'type', 'recipe', 'centerid', 'createdBy','foodtype','notes','RecipeVideolink'];

    /**
     * Get ingredients associated with this recipe
     */
    public function ingredients()
    {
        return $this->belongsToMany(
            IngredientModel::class,
            'recipe_ingredients',
            'recipeId',
            'ingredientId'
        )->select('ingredients.id', 'ingredients.name');
    }

    /**
     * Get media (images/videos) associated with this recipe
     */
    public function media()
    {
        return $this->hasMany(RecipeMediaModel::class, 'recipeid', 'id');
    }
}
