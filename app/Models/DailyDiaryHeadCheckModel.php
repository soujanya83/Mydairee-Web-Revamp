<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiaryHeadCheckModel extends Model
{
     protected $table = "dailydiaryheadcheck";
    protected $fillable = [
'headcount',
'diarydate',
'time',
'signature',
'roomid',
'comments',
'createdBy',
    ];
}
