<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PTMRoom extends Model
{
    use HasFactory;

    protected $table = 'ptmroom';
    protected $fillable = ['ptmid', 'roomid'];
    public $timestamps = false;
}
