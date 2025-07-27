<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ELYFActivity extends Model
{
    protected $table = "eylfactivity";
    protected $fillable = [
                'outcomeId',
                'title',
                'added_by',
                'added_at',
    ];

     public function outcome()
    {
        return $this->belongsTo(EYLFOutcome::class, 'outcomeId', 'id');
    }
}
