<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MontessoriActivity extends Model
{
    use HasFactory;

    protected $table = 'montessoriactivity';

    protected $primaryKey = 'idActivity'; // custom primary key

    public $incrementing = true; // since it's auto-increment
    protected $keyType = 'int';

    public $timestamps = false;


    protected $fillable = [
        'idSubject',
        'title',
        'added_by',
    ];

    // 🔗 Relationship to MontessoriSubject
    // public function subject()
    // {
    //     return $this->belongsTo(MontessoriSubject::class, 'idSubject');
    // }

    public function subject()
    {
        return $this->belongsTo(MontessoriSubject::class, 'idSubject', 'idSubject');
    }



public function subActivities()
{
    return $this->hasMany(MontessoriSubActivity::class, 'idActivity', 'idActivity');
}



}
