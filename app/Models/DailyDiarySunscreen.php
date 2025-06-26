<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiarySunscreen extends Model
{
    protected $table = "dailydiarysunscreen";
    // public $timestamps = false;
    protected $fillable = [
    'id',
    'childid',
    'diarydate',
    'startTime',
    'comments',
    'createdBy',
    'createdAt',
];

}
