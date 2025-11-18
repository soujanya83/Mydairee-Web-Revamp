<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiaryAfternoonTea extends Model
{
    protected $table = "dailydiaryafternoontea";
    // public $timestamps = false;
    protected $fillable = [
        'childid',
        'diarydate',
        'startTime',
        'item',
        'calories',
        'qty',
        'comments',
        'createdBy',
        'createdAt',
    ];
}
