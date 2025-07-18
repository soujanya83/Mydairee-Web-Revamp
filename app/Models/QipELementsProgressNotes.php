<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipELementsProgressNotes extends Model
{
    protected $table = 'qip_elements_progressnotes';
    // public $timestamps = false;

    protected $fillable = ['qipid', 'element_id', 'notetext','added_by','approved_by'];
}
