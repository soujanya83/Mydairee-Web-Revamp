<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'userid',
        'username',
        'room_leader',
        'emailid',
        'email',
        'password',
        'center_status',
        'contactNo',
        'name',
        'dob',
        'gender',
        'imageUrl',
        'userType',
        'title',
        'status',
        'AuthToken',
        'deviceid',
        'devicetype',
        'companyLogo',
        'theme',
        'wifi_status',
        'image_position',
        'created_by',
        'email_verified_at',
        'has_seen_login_notice',
        'admin'
    ];



    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
        ];
    }

    public function children()
    {
        return $this->belongsToMany(Child::class, 'childparent', 'parentid', 'childid')
            ->withPivot('relation');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_staff', 'staffid', 'roomid');
    }
    public function reflections()
    {
        return $this->hasMany(ReflectionStaff::class, 'staffid');
    }


    public function parents()
    {
        return $this->belongsToMany(User::class, 'childparent', 'childid', 'parentid', 'childid', 'userid');
    }

 public function permissions()
{
    return $this->hasMany(Permission::class, 'userid', 'userid');
}
}
