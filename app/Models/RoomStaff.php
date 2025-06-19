<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomStaff extends Model
{
    use HasFactory;

    protected $table = 'room_staff';

    public $timestamps = false;

    protected $fillable = [
        'roomid',
        'staffid',
    ];

    // 🔗 Relationship to Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'roomid');
    }

    // 🔗 Relationship to User (staff)
    public function staff()
    {
        return $this->belongsTo(User::class, 'staffid');
    }
}
