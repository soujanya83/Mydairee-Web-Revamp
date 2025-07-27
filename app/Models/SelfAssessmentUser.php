<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfAssessmentUser extends Model
{
    protected $table = 'self_assessment_users';
    // public $timestamps = false;

    protected $fillable = ['self_assess_id','userid','added_by'];
}
