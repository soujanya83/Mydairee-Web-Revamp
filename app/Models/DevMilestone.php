<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevMilestone extends Model
{
    use HasFactory;

    protected $table = 'devmilestone'; // explicitly set if not plural

    public $timestamps = false;

    protected $fillable = [
        'ageGroup',
    ];

    public function milestones()
{
    return $this->hasMany(DevMilestoneMain::class, 'ageId');
}

public function mains() {
    return $this->hasMany(DevMilestoneMain::class, 'ageId', 'id');
}

}
