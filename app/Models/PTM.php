<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PTM extends Model
{
    use HasFactory;
    protected $table = 'ptm'; 
    protected $casts = ['ptmdate' => 'date:Y-m-d',];

    protected $fillable = [
        'userId',
        'title',
        'objective',
        'status',
        'ptmdate',
        'centerid',
        'slot'

    ];
   
    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }

    public function children()
    {
        return $this->belongsToMany(Child::class, 'ptmchild', 'ptmId', 'childId')->orderBy('name');
    }

    public function room()
    {
        return $this->belongsToMany(Room::class, 'ptmroom', 'ptmId', 'roomId')->orderBy('name');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'ptmstaff', 'ptmId', 'staffid')->orderBy('name');
    }


    public function getFinalDateAttribute()
    {
        $latestReschedule = $this->reschedules()->latest('created_at')->first();

        if ($latestReschedule) {
            return $latestReschedule->ptmdate;
        }

        return $this->ptmDates->min('date');
    }

    public function getFinalSlotAttribute()
    {
        $latestReschedule = $this->reschedules()->latest('created_at')->first();


        if ($latestReschedule) {

            if (method_exists($latestReschedule, 'rescheduleslot')) {
                $related = $latestReschedule->rescheduleslot;
                if ($related && !empty($related->slot)) {
                    return $related->slot;
                }
            }

 
            if (!empty($latestReschedule->ptmslotid) && !empty($latestReschedule->ptmslotid->slot ?? null)) {
                return $latestReschedule->ptmslotid->slot;
            }
            if (!empty($latestReschedule->newslot)) {
                return $latestReschedule->newslot;
            }
        }

        return $this->ptmSlots->isNotEmpty() ? $this->ptmSlots->first()->slot : null;
    }

    
    public function getFirstPtmDateAttribute()
    {
        $date = $this->ptmDates->min('date');
        if (!$date) return null;
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    
    public function center()
    {
        return $this->belongsTo(Center::class, 'centerid');
    }


    public function ptmDates()
    {
        return $this->hasMany(PTMDate::class, 'ptm_id')->orderBy('id', 'ASC');
    }

    public function ptmSlots()
    {
        return $this->hasMany(PTMSlot::class, 'ptm_id');
    }

    public function reschedules()
    {
        return $this->hasMany(PTMReschedule::class, 'ptmid');
    }




}
