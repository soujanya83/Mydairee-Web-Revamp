<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MontessoriActivity extends Model
{
    protected $table = "montessoriactivity";
     public $timestamps = false;
    protected $fillable = [
                'idSubject',
                'title',
                'added_by',
                'added_at',
                    ];

   public function subActivities()
{
    return $this->hasMany(MontessoriSubActivity::class, 'idActivity', 'idActivity');
}

    public function parentActivity()
    {
        return $this->belongsTo(MontessoriActivity::class, 'parent_id');
    }
}
