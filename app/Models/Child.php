<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $table = 'child';
    public $timestamps = false;

    protected $fillable = [
        'name', 'lastname', 'dob', 'startDate', 'room',
        'imageUrl', 'gender', 'status', 'daysAttending',
        'createdBy', 'createdAt','centerid'
    ];

    public function parents()
    {
        return $this->belongsToMany(User::class, 'childparent', 'childid', 'parentid')
                    ->withPivot('relation');
    }
}
