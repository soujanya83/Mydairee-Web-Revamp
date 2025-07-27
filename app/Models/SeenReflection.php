<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeenReflection extends Model
{
    use HasFactory;

    protected $table = 'seen_reflections';

    protected $fillable = ['user_id', 'reflection_id'];


    public function reflection()
    {
        return $this->belongsTo(Reflection::class, 'reflection_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    
}
