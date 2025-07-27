<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiaryBreakfast extends Model
{
    protected $table = "dailydiarybreakfast";
    public $timestamps = false;
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
