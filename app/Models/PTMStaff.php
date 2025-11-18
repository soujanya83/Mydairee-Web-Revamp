<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTMStaff extends Model
{
    use HasFactory;

    protected $table = 'ptmStaff'; 
    public $timestamps = false;


    protected $fillable = [
        'ptmId',
        'staffid',
    ];

  
    // public function ptm() {
    //     return $this->belongsTo(PTM::class, 'ptmId');
    // }

    // public function user() {
    //     return $this->belongsTo(User::class, 'userid');
    // }
}
