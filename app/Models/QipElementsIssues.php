<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipElementsIssues extends Model
{
    protected $table = 'qip_elements_issues';
    // public $timestamps = false;

    protected $fillable = ['qipid', 'element_id', 'issueIdentified','outcome','priority','expectedDate','successMeasure','howToGetOutcome','addedBy','status'];
}
