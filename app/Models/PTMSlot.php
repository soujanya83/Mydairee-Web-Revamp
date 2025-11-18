<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTMSlot extends Model
{
    use HasFactory; 
    protected $table = 'ptmslot'; 
    protected $fillable = ['ptm_id', 'ptmdate_id', 'slot']; 

    public function ptm()
    {
        return $this->belongsTo(Ptm::class, 'ptm_id');
    }

    public function ptmDate()
    {
        return $this->belongsTo(PTMDate::class, 'ptmdate_id');
    }


}
