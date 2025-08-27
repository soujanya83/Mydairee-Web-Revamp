<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationStaff extends Model
{
    use HasFactory;

    protected $table = 'observationStaff'; // explicitly set if not plural
    public $timestamps = false;


    protected $fillable = [
        'observationId',
        'userid',
    ];

    // Optional relationships if needed:
    public function observation() {
        return $this->belongsTo(Observation::class, 'observationId');
    }

    public function user() {
        return $this->belongsTo(User::class, 'userid');
    }
}
