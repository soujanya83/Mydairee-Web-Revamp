<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementChildModel extends Model
{
    protected $table = "announcementchild";
    public $timestamps = false;
    protected $fillable = [
        'aid',
        'childid'
    ];
}
