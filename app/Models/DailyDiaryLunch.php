<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiaryLunch extends Model
{
      protected $table = 'dailydiarylunch';
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
