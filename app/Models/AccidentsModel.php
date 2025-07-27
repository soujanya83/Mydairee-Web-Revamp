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
'date',
'time',
'person_sign',
'childid',
'child_name',
'child_dob',
'child_age',
'child_gender',
'incident_date',
'incident_time',
'incident_location',
'witness_name',
'witness_sign',
'witness_date',
'injury_image',
'gen_actyvt',
'cause',
'illness_symptoms',
'missing_unaccounted',
'taken_removed',
'action_taken',
'emrg_serv_attend',
'med_attention',
'med_attention_details',
'prevention_step_1',
'prevention_step_2',
'prevention_step_3',
'parent1_name',
'contact1_method',
'contact1_date',
'contact1_time',
'contact1_made',
'contact1_msg',
'parent2_name',
'contact2_method',
'contact2_date',
'contact2_time',
'contact2_made',
'contact2_msg',
'responsible_person_name',
'responsible_person_sign',
'rp_internal_notif_date',
'rp_internal_notif_time',
'nominated_supervisor_name',
'nominated_supervisor_sign',
'nominated_supervisor_date',
'nominated_supervisor_time',
'ext_notif_other_agency',
'enor_date',
'enor_time',
'ext_notif_regulatory_auth',
'enra_date',
'enra_time',
'ack_parent_name',
'ack_date',
'ack_time',
'add_notes',
'added_by',
'added_at',
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
