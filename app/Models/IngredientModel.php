<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientModel extends Model
{
    protected $table='ingredients';
    protected $fillable=['name', 'ingredient_type_id'];
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(IngredientTypeModel::class, 'ingredient_type_id', 'id');
    }

}
