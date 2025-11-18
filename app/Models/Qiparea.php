<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qiparea extends Model
{
    protected $table = 'qip_area';
    public $timestamps = false;

    protected $fillable = ['title', 'color', 'about'];
}
