<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qip extends Model
{
    protected $table = 'qip';
    // public $timestamps = false;

    protected $fillable = ['centerId', 'name', 'created_by'];
}
