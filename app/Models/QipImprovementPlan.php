<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipImprovementPlan extends Model
{
    protected $table = 'qip_improvement_plan';
    public $timestamps = false;

    protected $fillable = ['qipid', 'areaId', 'standard','issue','outcome','priority','outcome_step','measure','by_when','progress'];
}
