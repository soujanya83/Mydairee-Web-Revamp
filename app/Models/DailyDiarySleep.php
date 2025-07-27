<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiarySleep extends Model
{

    protected $table = "dailydiarysleep";
    public $timestamps = false;
   protected $fillable = [
    'childid',
    'diarydate',
    'startTime',
    'endTime',
    'comments',
    'createdBy',
    'createdAt',
];
}
