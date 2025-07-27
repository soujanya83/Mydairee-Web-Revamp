<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EYLFActivity extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eylfactivity';

    protected $fillable = [
        'outcomeId',
        'title',
        'added_by',
    ];

    // 🔗 Relationship to EYLFOutcome
    public function outcome()
    {
        return $this->belongsTo(EYLFOutcome::class, 'outcomeId');
    }

    public function subActivities() {
        return $this->hasMany(EYLFSubActivity::class, 'activityid', 'id');
    }

public function observationLinks()
{
    return $this->hasMany(ObservationEYLF::class, 'eylfActivityId');
}


}