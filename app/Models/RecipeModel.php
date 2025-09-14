<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeModel extends Model
{
    protected $table = 'recipes';
    public $timestamps = false;

    protected $fillable = ['itemName', 'type', 'recipe', 'centerid', 'createdBy','foodtype','notes','RecipeVideolink'];
}
