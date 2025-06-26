<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccidentIllnessModel extends Model
{
    public $timestamps = false;
    protected $table ="accident_illness";
    protected $fillable = [
'accident_id',
'abrasion',
'allergic_reaction',
'amputation',
'anaphylaxis',
'asthma',
'bite_wound',
'broken_bone',
'burn',
'choking',
'concussion',
'crush',
'cut',
'drowning',
'eye_injury',
'electric_shock',
'infectious_disease',
'high_temperature',
'ingestion',
'internal_injury',
'poisoning',
'rash',
'respiratory',
'seizure',
'sprain',
'stabbing',
'tooth',
'venomous_bite',
'other',
'remarks',
    ];
}
