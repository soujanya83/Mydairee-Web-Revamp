<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    protected $table = 'centers';
    protected $fillable = [
        'centerName',
        'user_id',
        'adressStreet',
        'addressCity',
        'addressZip',
        'addressState'

    ];
}
