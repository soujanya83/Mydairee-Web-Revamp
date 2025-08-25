<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReflectionChild extends Model
{
    use HasFactory;

    protected $table = 'reflectionchild';

    public $timestamps = false;

    protected $fillable = [
        'reflectionid',
        'childid',
    ];

    // 🔗 Relationship to Reflection
    public function reflection()
    {
        return $this->belongsTo(Reflection::class, 'reflectionid');
    }

    // 🔗 Relationship to Child
    public function child()
    {
        return $this->belongsTo(Child::class, 'childid');
    }

    public function childDetails()
{
    return $this->belongsTo(Child::class, 'childid')
                ->select('id', 'name', 'lastname','imageUrl'); // only select name and lastname
}
}
