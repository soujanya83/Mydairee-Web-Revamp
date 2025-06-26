<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table='room';



    public function staff()
{
    return $this->belongsToMany(User::class, 'room_staff', 'roomid', 'staffid');
}
    public $timestamps = false;

    protected $fillable=['id','name','userId','color','ageFrom','ageTo','capacity','status','centerid','created_by'];
}
