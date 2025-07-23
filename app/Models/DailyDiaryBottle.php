<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiaryBottle extends Model
{
    protected $table = "dailydiarybottle";
    public $timestamps = false;

    // public $timestamps = false;
   protected $fillable = [
    'childid',
    'diarydate',
    'startTime',
    'createdBy',
];

}
