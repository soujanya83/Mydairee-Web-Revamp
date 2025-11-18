<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationEYLF extends Model
{
    use HasFactory;

    protected $table = 'observationeylf';
    public $timestamps = false;

    protected $fillable = [
        'observationId',
        'eylfActivityId',
        'eylfSubactivityId',
    ];

    // 🔗 Relationship to Observation
    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observationId');
    }

    // 🔗 Relationship to EYLFActivity
    public function activity()
    {
        return $this->belongsTo(EYLFActivity::class, 'eylfActivityId');
    }

    // 🔗 Relationship to EYLFSubActivity
    public function subActivity()
    {
        return $this->belongsTo(EYLFSubActivity::class, 'eylfSubactivityId');
    }
}
