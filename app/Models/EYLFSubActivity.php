<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EYLFSubActivity extends Model
{
    use HasFactory;

    protected $table = 'eylfsubactivity';
    public $timestamps = false;

    protected $fillable = [
        'activityid',
        'title',
        'added_by',
    ];

    // 🔗 Relationship to EYLFActivity
    public function activity()
    {
        return $this->belongsTo(EYLFActivity::class, 'activityid');
    }

    public function observationLinks()
{
    return $this->hasMany(ObservationEYLF::class, 'eylfSubactivityId');
}
}
