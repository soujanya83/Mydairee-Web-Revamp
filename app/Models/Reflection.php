<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Reflection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reflection';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'about',
        'centerid',
        'status',
        'eylf',
        'roomids',
        'createdBy',
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
    return $this->hasMany(ReflectionChild::class, 'reflectionid');
}

public function media()
{
    return $this->hasMany(ReflectionMedia::class, 'reflectionid');
}

public function staff()
{
    return $this->hasMany(ReflectionStaff::class, 'reflectionid');
}
public function SeenReflection()
{
    return $this->hasMany(SeenReflection::class, 'reflection_id');
}

public function Seen()
{
    return $this->hasMany(SeenReflection::class, 'reflection_id');
}

    protected static function booted()
    {
        static::deleting(function (Reflection $reflection) {
            if (! $reflection->isForceDeleting()) {
                if (Auth::check() && empty($reflection->deleted_by)) {
                    $reflection->forceFill(['deleted_by' => Auth::id()])->saveQuietly();
                }
                return;
            }

            foreach ($reflection->media as $media) {
                if (!empty($media->mediaUrl) && file_exists(public_path($media->mediaUrl))) {
                    @unlink(public_path($media->mediaUrl));
                }
            }

            $reflection->children()->delete();
            $reflection->media()->delete();
            $reflection->staff()->delete();
            $reflection->Seen()->delete();
        });
    }






}