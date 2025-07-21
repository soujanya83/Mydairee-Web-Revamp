<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfAssessment extends Model
{
    protected $table = 'self_assessment';
    // public $timestamps = false;

    protected $fillable = ['centerid','name','added_by'];
}
