<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramPlan extends Model
{
    protected $table = "programplan";

    protected $fillable = [
	'roomid',
    'name',
    'startDate',
    'endDate',
    'createdAt',
    'createdBy',
    'updateBy',	
    'updateAt'	
    ];

    
}
