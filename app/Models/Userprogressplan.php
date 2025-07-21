<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userprogressplan extends Model
{
    use HasFactory;

    protected $table = 'userprogressplan';
    // public $timestamps = false;

    protected $fillable = [
        'observationId',
        'childid',
        'subid',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    // 🔗 Relationship to Observation
    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observationId');
    }

    // 🔗 Relationship to MontessoriSubActivity
    public function montessoriSubActivity()
    {
        return $this->belongsTo(MontessoriSubActivity::class, 'subid');
    }

    public function subActivity()
    {
        return $this->belongsTo(MontessoriSubActivity::class, 'subid');
    }

    public function child() {
        return $this->belongsTo(Child::class, 'childid');
    }
}
