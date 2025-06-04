<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
}
