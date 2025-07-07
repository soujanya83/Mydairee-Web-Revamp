<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeenObservation extends Model
{
    use HasFactory;

    protected $table = 'seen_observations';

    protected $fillable = ['user_id', 'observation_id'];


    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observation_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
