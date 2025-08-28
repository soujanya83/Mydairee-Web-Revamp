<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccidentsModel extends Model
{
    protected $table = 'accidents';
    public $timestamps = false;

    protected $fillable = [
        'centerid',
        'roomid',
        'person_name',
        'person_role',
        'service_name',
        'made_record_date',
        'made_record_time',
        'childid','made_person_sign',
        'child_name',
        'child_dob',
        'child_age',
        'child_gender',
        'incident_date',
        'incident_time',
        'incident_location',
        'location_of_incident',
        'witness_name',
        'witness_date',
        'witness_sign',
        'details_injury',
        'circumstances_leading',
        'circumstances_child_missingd',
        'circumstances_child_removed',
        'injury_image',
        'remarks',
        'action_taken',
        'emrg_serv_attend',
        'emrg_serv_time',
        'emrg_serv_arrived',
        'med_attention',
        'med_attention_details',
        'provideDetails_minimise',
        'parent1_name',
        'carers_date',
        'carers_time',
        'director_educator_coordinator',
        'educator_date',
        'educator_time',
        'other_agency',
        'other_agency_date',
        'other_agency_time',
        'regulatory_authority',
        'regulatory_authority_date',
        'regulatory_authority_time',
        'ack_parent_name',
        'ack_date',
        'ack_time',
        'final_sign',
        'add_notes',
        'added_by',
        'added_at'
    ];

    public function childParent()
    {
        return $this->hasOne(ChildParent::class, 'childid', 'childid');
    }

    // In App\Models\AccidentsModel.php

    public function child()
    {
        return $this->belongsTo(Child::class, 'childid', 'id');
    }

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }
}
