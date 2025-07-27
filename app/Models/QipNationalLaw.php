<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipNationalLaw extends Model
{
    protected $table = 'qip_national_law';
    public $timestamps = false;

    protected $fillable = ['areaId', 'section', 'about','element'];
}
