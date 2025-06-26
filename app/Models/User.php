<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $fillable = [
    'userid',
    'username',
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
    'image_position',
    'created_by',
    'email_verified_at',
];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
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

public function parents()
{
    return $this->belongsToMany(User::class, 'childparent', 'childid', 'parentid', 'childid', 'userid');
}
}
