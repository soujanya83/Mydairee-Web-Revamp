<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MontessoriSubActivity extends Model
{
    use HasFactory;

    protected $table = 'montessorisubactivity';

    protected $primaryKey = 'idSubActivity'; // Custom primary key
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;


    protected $fillable = [
        'idActivity',
        'title',
        'added_by',
    ];

    // 🔗 Relationship to MontessoriActivity
    public function activity()
    {
        return $this->belongsTo(MontessoriActivity::class, 'idActivity');
    }

    public function observationLinks()
{
    return $this->hasMany(ObservationMontessori::class, 'idSubActivity');
}

public function montessoriLinks()
{
    return $this->hasMany(ObservationMontessori::class, 'idSubActivity');
}

// newly added
public function montessoriSubActivityAccess()
{
    return $this->hasMany(MontessoriSubActivityAccess::class, 'idSubActivity', 'idSubActivity');
}
}
