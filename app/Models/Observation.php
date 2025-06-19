<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Observation extends Model
{
    use HasFactory;

    protected $table = 'observation'; // explicitly set if not plural

    protected $fillable = [
        'userId',
        'obestitle',
        'title',
        'notes',
        'room',
        'reflection',
        'future_plan',
        'child_voice',
        'status',
        'approver',
        'centerid',
    ];

    // Optionally, if you use timestamps (created_at, updated_at), leave this as is.
    // If not, add this:
    // public $timestamps = false;

    // Optional: define relationships if needed later, like:
    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }
    public function child() {
        return $this->hasMany(ObservationChild::class, 'observationId');
    }

    public function devMilestoneSubs()
{
    return $this->hasMany(ObservationDevMilestoneSub::class, 'observationId');
}

public function eylfLinks()
{
    return $this->hasMany(ObservationEYLF::class, 'observationId');
}

public function montessoriLinks()
{
    return $this->hasMany(ObservationMontessori::class, 'observationId');
}

public function media()
{
    return $this->hasMany(ObservationMedia::class, 'observationId');
}

public function links()
{
    return $this->hasMany(ObservationLink::class, 'observationId');
}

}
