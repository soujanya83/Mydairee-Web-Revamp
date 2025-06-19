<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MontessoriSubActivity extends Model
{
    protected $table = "montessorisubactivity";
    public $timestamps = false;
    protected $fillable = [
'idActivity',
'title',
'subject',
'imageUrl',
'added_by',
'added_at',
    ];

public function activity()
{
    return $this->belongsTo(MontessoriActivity::class, 'idActivity', 'idActivity');
}
 
}
