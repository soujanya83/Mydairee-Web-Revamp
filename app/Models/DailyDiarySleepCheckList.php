<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiarySleepCheckList extends Model
{
    public $timestamps = false;
     protected $table = "dailydiarysleepchecklist";
    protected $fillable = [
'childid',
'roomid',
'diarydate',
'time',
'breathing',
'body_temperature',
'createdBy',
'notes',
'signature'
    ];
}
