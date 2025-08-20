<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReflectionStaff extends Model
{
    use HasFactory;

    protected $table = 'reflectionstaff';

    public $timestamps = false;

    protected $fillable = [
        'reflectionid',
        'staffid',
    ];

    // 🔗 Relationship to Reflection
    // public function reflection()
    // {
    //     return $this->belongsTo(Reflection::class, 'reflectionid');
    // }

    // 🔗 Relationship to User (staff)
    public function staff()
    {
        return $this->belongsTo(User::class, 'staffid');
    }

   public function staffDetails()
{
    return $this->belongsTo(User::class, 'staffid', 'userid')
                ->select('userid', 'name','imageUrl'); // only select name
}

}
