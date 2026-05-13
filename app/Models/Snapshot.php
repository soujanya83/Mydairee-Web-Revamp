<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Snapshot extends Model
{
    use HasFactory, SoftDeletes;

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
    public function deletedByUser()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
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

    protected static function booted()
    {
        static::deleting(function (Snapshot $snapshot) {
            if (! $snapshot->isForceDeleting()) {
                return;
            }

            foreach ($snapshot->media as $media) {
                if (!empty($media->mediaUrl) && file_exists(public_path($media->mediaUrl))) {
                    @unlink(public_path($media->mediaUrl));
                }
            }

            $snapshot->children()->delete();
            $snapshot->media()->delete();
        });
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