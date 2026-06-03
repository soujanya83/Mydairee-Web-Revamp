<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientTypeModel extends Model
{
    protected $table = 'ingredient_types';

    public $timestamps = false;

    protected $fillable = ['name'];

    public function ingredients()
    {
        return $this->hasMany(IngredientModel::class, 'ingredient_type_id', 'id');
    }
}