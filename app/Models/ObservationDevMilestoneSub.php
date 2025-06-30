<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationDevMilestoneSub extends Model
{
    use HasFactory;

    protected $table = 'observationdevmilestonesub';

    public $timestamps = false;

    protected $fillable = [
        'observationId',
        'devMilestoneId',
        'idExtra',
        'assessment',
    ];

    // 🔗 Relationship to Observation
    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observationId');
    }

    // 🔗 Relationship to DevMilestoneSub
    public function devMilestoneSub()
    {
        return $this->belongsTo(DevMilestoneSub::class, 'devMilestoneId');
    }

    public function devMilestone()
    {
        return $this->belongsTo(DevMilestoneSub::class, 'devMilestoneId');
    }
}
