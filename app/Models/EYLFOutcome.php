<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EYLFOutcome extends Model
{
    protected $table = "eylfoutcome";
    protected $fillable = [
                    'title',
                    'name',
                    'added_by',
                    'added_at',
    ];

//  public function outcome()
//     {
//         return $this->belongsTo(EYLFOutcome::class, 'outcomeId', 'id');
//     }
    public function activities()
{
    return $this->hasMany(ELYFActivity::class, 'outcomeId', 'id');
}
}
