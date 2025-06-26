<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiaryMorningTea extends Model
{
    protected $table = "dailydiarymorningtea";
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
