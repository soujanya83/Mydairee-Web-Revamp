<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationLink extends Model
{
    use HasFactory;

    protected $table = 'observationlinks';
    public $timestamps = false;

    protected $fillable = [
        'observationId',
        'linkid',
        'linktype',
    ];

    // 🔗 Relationship to Observation
    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observationId');
    }
}
