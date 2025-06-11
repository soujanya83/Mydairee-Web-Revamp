<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Observation extends Model
{
    use HasFactory;

    protected $table = 'observation'; // explicitly set if not plural

    protected $fillable = [
        'userId',
        'obestitle',
        'title',
        'notes',
        'room',
        'reflection',
        'future_plan',
        'child_voice',
        'status',
        'approver',
        'centerid',
    ];

    // Optionally, if you use timestamps (created_at, updated_at), leave this as is.
    // If not, add this:
    // public $timestamps = false;

    // Optional: define relationships if needed later, like:
    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }
}
