<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class PTMReschedule extends Model
{
    use HasFactory;

    protected $table = 'rescheduleptm';

    protected $fillable = [
        'ptmid',
        'ptmdateid',
        'userid',
        'ptmslotid',
        'childid',
        'reason',
    ];

     public function ptm()
    {
        return $this->belongsTo(PTM::class, 'ptmid');
    }


     public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function rescheduledate()
    {
        return $this->belongsTo(PTMDate::class,'ptmdateid', 'id');
    }

    public function rescheduleslot()
     {
        return $this->belongsTo(PTMSlot::class,'ptmslotid', 'id');
    }

    public function child()
{
    return $this->belongsTo(\App\Models\Child::class, 'childid', 'id');
}
}
