<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTMChild extends Model
{
    use HasFactory;

    protected $table = 'ptmchild'; 

    protected $fillable = [
        'ptmId',
        'childId',
    ];

    public $timestamps = false;
    // public function ptm() {
    //     return $this->belongsTo(ptm::class, 'ptmId');
    // }

    // public function child() {
    //     return $this->belongsTo(Child::class, 'childId');
    // }
}
