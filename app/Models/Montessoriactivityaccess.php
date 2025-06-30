<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Montessoriactivityaccess extends Model
{
    protected $table = "montessoriactivityaccess";
     public $timestamps = false;
    protected $fillable = [
            'idActivity',
            'centerid',
            'added_by',
            'added_at',
        ];
}
