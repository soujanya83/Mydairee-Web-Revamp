<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PubicHoliday_Model extends Model
{
    protected $table = "publicholidays";
    protected $fillable = [
        'state',
        'month',
        'date',
        'occasion',

    ];
}
