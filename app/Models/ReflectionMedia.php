<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReflectionMedia extends Model
{
    use HasFactory;

    protected $table = 'reflectionmedia';

    public $timestamps = false;

    protected $fillable = [
        'reflectionid',
        'mediaUrl',
        'mediaType',
    ];

    // 🔗 Relationship to Reflection
    public function reflection()
    {
        return $this->belongsTo(Reflection::class, 'reflectionid');
    }
}
