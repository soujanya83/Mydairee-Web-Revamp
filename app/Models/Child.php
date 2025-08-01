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

    public function room()
{
    return $this->belongsTo(Room::class, 'room');
}

    public function observationChildren()
    {
        return $this->hasMany(ObservationChild::class, 'childId');
    }

    public function observations()
    {
        return $this->hasManyThrough(Observation::class, ObservationChild::class, 'childId', 'id', 'id', 'observationId');
    }

    public function reflections()
{
    return $this->hasMany(ReflectionChild::class, 'childid');
}
}
