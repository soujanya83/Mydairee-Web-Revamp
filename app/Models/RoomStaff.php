<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomStaff extends Model
{
    protected $table='room_staff';
    public $timestamps = false;

    protected $fillable=['roomid','staffid'];
}
