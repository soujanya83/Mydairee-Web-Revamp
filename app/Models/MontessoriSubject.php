<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MontessoriSubject extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'montessorisubjects';

    protected $fillable = [
        'name',
    ];


public function activities()
{
    return $this->hasMany(MontessoriActivity::class, 'idSubject', 'idSubject');
}

}
