<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiaryToileting extends Model
{
  protected $table = 'dailydiarytoileting';

    protected $fillable = [
        'childid',
        'diarydate',
        'startTime',
        'nappy',
        'potty',
        'toilet',
        'signature',
        'comments',
        'status',
        'createdBy',
        'createdAt',
    ];

    // public $timestamps = false;
}
