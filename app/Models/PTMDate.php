<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTMDate extends Model
{
    use HasFactory;

    protected $table = 'ptmdate';
    protected $fillable = ['ptm_id', 'date'];

    
    public function ptm()
    {
        return $this->belongsTo(Ptm::class, 'ptm_id');
    }
}
