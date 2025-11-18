<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramPlanTemplateDetailsAdd extends Model
{
    protected $table = "programplantemplatedetailsadd";
    protected $fillable = [
'room_id',
'months',
'years',
'educators',
'centerid',
'created_by',
'children',
'focus_area',
'practical_life',
'practical_life_experiences',
'sensorial',
'sensorial_experiences',
'math',
'math_experiences',
'language',
'language_experiences',
'culture',
'culture_experiences',
'art_craft',
'art_craft_experiences',
'eylf',
'outdoor_experiences',
'inquiry_topic',
'sustainability_topic',
'special_events',
'children_voices',
'families_input',
'group_experience',
'spontaneous_experience',
'mindfulness_experiences',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');

    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');

    }
}
