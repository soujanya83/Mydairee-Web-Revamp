<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usercenter extends Model
{
    // Table name
    protected $table = 'usercenters';

    // Fillable fields
    protected $fillable = [
        'userid',
        'centerid',
    ];

    // Optional relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'centerid', 'id');
    }
}
