<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnapshotMedia extends Model
{
    use HasFactory;

    protected $table = 'snapshotmedia';

    public $timestamps = false;

    protected $fillable = [
        'snapshotid',
        'mediaUrl',
        'mediaType',
    ];

    // 🔗 Relationship to Reflection
    public function snapshot()
    {
        return $this->belongsTo(Snapshot::class, 'snapshotid');
    }
}
