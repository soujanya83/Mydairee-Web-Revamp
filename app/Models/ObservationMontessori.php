<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationMontessori extends Model
{
    use HasFactory;

    protected $table = 'observationmontessori';
    public $timestamps = false;

    protected $fillable = [
        'observationId',
        'idSubActivity',
        'idExtra',
        'assesment',
    ];

    // 🔗 Relationship to Observation
    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observationId');
    }

    // 🔗 Relationship to MontessoriSubActivity
    public function montessoriSubActivity()
    {
        return $this->belongsTo(MontessoriSubActivity::class, 'idSubActivity');
    }

    public function subActivity()
    {
        return $this->belongsTo(MontessoriSubActivity::class, 'idSubActivity');
    }
}
