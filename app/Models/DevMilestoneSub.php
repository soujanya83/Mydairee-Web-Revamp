<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevMilestoneSub extends Model
{
    use HasFactory;

    protected $table = 'devmilestonesub';

    public $timestamps = false;

    protected $fillable = [
        'milestoneid',
        'name',
        'added_by',
    ];

    // 🔗 Relationship to DevMilestoneMain
    public function milestoneMain()
    {
        return $this->belongsTo(DevMilestoneMain::class, 'milestoneid');
    }

    public function observationLinks()
{
    return $this->hasMany(ObservationDevMilestoneSub::class, 'devMilestoneId');
}

 
public function main()
{
    return $this->belongsTo(DevMilestoneMain::class, 'milestoneid');
}

public function observationDevMilestoneSubs()
{
    return $this->hasMany(ObservationDevMilestoneSub::class, 'devMilestoneId');
}

// Relationship to get the milestone through main
public function milestone()
{
    return $this->hasOneThrough(DevMilestone::class, DevMilestoneMain::class, 'id', 'id', 'milestoneid', 'ageId');
}

}
