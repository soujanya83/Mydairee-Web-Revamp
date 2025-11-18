<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MontessoriSubActivityAccess extends Model
{
    protected $table = 'montessorisubactivityaccess';
    public $timestamps = false;

    protected $fillable = [
'idSubActivity',
'centerid',
'added_by',
'added_at',
    ];


}
