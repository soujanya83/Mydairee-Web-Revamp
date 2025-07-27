<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDiarySettings extends Model
{
    public $timestamps = false;
    protected $table = 'dailydiarysettings';
    protected $fillable = [
'centerid',
'breakfast',
'morningtea',
'lunch',
'sleep',
'afternoontea',
'latesnacks',
'sunscreen',
'toileting',
    ];
}
