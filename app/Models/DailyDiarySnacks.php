<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiarySnacks extends Model
{
     protected $table = 'dailydiarysnacks';
    public $timestamps = false;

protected $fillable = [
    'id',
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
