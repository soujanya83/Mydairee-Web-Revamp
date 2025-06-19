<?php

namespace App\Models;
use App\Models\MontessoriActivity;

use Illuminate\Database\Eloquent\Model;

class MontessoriSubject extends Model
{
   protected $primaryKey = 'idSubject';
   protected $table = "montessorisubjects";
    public $timestamps = false;


    protected $fillable = ['name'];

    public function activities()
    {
        return $this->hasMany(MontessoriActivity::class, 'idSubject', 'idSubject');
    }
}
