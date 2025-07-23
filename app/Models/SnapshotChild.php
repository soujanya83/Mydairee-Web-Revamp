<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnapshotChild extends Model
{
    use HasFactory;

    protected $table = 'snapshotchild';

    public $timestamps = false;

    protected $fillable = [
        'snapshotid',
        'childid',
    ];

    // 🔗 Relationship to Reflection
    public function snapshot()
    {
        return $this->belongsTo(Snapshot::class, 'snapshotid');
    }

    // 🔗 Relationship to Child
    public function child()
    {
        return $this->belongsTo(Child::class, 'childid');
    }
}
