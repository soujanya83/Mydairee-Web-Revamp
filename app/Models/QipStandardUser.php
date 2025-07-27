<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipStandardUser extends Model
{
    protected $table = 'qip_standards_user';
    // public $timestamps = false;

    protected $fillable = ['areaid','qipid','standardid','	elementid','userid','added_by'];
}
