<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevMilestoneMain extends Model
{
    use HasFactory;

    protected $table = 'devmilestonemain';

    public $timestamps = false;


    protected $fillable = [
        'ageId',
        'name',
    ];

    // 🔗 Relationship to DevMilestone
    public function devMilestone()
    {
        return $this->belongsTo(DevMilestone::class, 'ageId');
    }

    public function subMilestones()
{
    return $this->hasMany(DevMilestoneSub::class, 'milestoneid');
}

public function subs() {
    return $this->hasMany(DevMilestoneSub::class, 'milestoneid', 'id');
}

  


public function milestone()
{
    return $this->belongsTo(DevMilestone::class, 'ageId');
}






}
