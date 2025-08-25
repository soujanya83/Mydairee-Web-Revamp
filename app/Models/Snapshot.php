<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    use HasFactory;

    protected $table = 'snapshot';

    protected $fillable = [
        'title',
        'about',
        'centerid',
        'roomids',
        'status',
        'createdBy',
        'educators'
    ];

    // 🔗 Optional: Relationship to User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    // 🔗 Optional: Relationship to center (if a Center model exists)
    public function center()
    {
        return $this->belongsTo(Center::class, 'centerid');
    }

    public function children()
{
    return $this->hasMany(SnapshotChild::class, 'snapshotid');
}

public function media()
{
    return $this->hasMany(SnapshotMedia::class, 'snapshotid');
}

// public function staff()
// {
//     return $this->hasMany(ReflectionStaff::class, 'reflectionid');
// }
// public function SeenReflection()
// {
//     return $this->hasMany(SeenReflection::class, 'reflection_id');
// }

// public function Seen()
// {
//     return $this->hasMany(SeenReflection::class, 'reflection_id');
// }


}