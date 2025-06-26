<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationComment extends Model
{
    use HasFactory;

    protected $table = 'observationcomments'; // explicitly set if not plural

    protected $fillable = [
        'observationId',
        'userId',
        'comments',
    ];

    // Optional relationships if needed:
    public function observation() {
        return $this->belongsTo(Observation::class, 'observationId');
    }

    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }
}
