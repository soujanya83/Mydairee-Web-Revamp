<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationMedia extends Model
{
    use HasFactory;

    protected $table = 'observationmedia';

    public $timestamps = false;

    protected $fillable = [
        'observationId',
        'mediaUrl',
        'mediaType',
        'caption',
        'priority',
    ];

    // 🔗 Relationship to Observation
    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observationId');
    }
}
