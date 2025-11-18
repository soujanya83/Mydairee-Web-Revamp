<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientModel extends Model
{
    protected $table='ingredients';
    protected $fillable=['name'];
    public $timestamps = false;

}
