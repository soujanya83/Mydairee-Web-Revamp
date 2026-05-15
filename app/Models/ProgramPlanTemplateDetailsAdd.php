<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProgramPlanTemplateDetailsAdd extends Model
{
    use SoftDeletes;

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
'working',
'notworking',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');

    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');

    }
    public function deletedByUser()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    protected static function booted()
    {
        static::deleting(function (ProgramPlanTemplateDetailsAdd $plan) {
            if ($plan->isForceDeleting()) {
                return;
            }

            if (Auth::check() && empty($plan->deleted_by)) {
                $plan->forceFill(['deleted_by' => Auth::id()])->saveQuietly();
            }
        });
    }
}
