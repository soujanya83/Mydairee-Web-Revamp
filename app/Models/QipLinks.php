<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipLinks extends Model
{
    protected $table = 'qip_links';
    // public $timestamps = false;

    protected $fillable = ['linkid', 'element_id', 'qip_id','linktype','added_by'];
}
