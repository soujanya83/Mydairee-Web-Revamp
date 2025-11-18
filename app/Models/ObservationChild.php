<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationChild extends Model
{
    use HasFactory;

    protected $table = 'observationchild'; // explicitly set if not plural

    protected $fillable = [
        'observationId',
        'childId',
    ];

    // Optional relationships if needed:
    public function observation() {
        return $this->belongsTo(Observation::class, 'observationId');
    }

    public function child() {
        return $this->belongsTo(Child::class, 'childId');
    }
}
