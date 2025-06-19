<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EYLFOutcome extends Model
{
    use HasFactory;

    protected $table = 'eylfoutcome';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'name',
        'added_by',
    ];


public function activities() {
    return $this->hasMany(EYLFActivity::class, 'outcomeId', 'id');
}
}
