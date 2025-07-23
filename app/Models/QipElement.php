<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipElement extends Model
{
    protected $table = 'qip_elements';
    public $timestamps = false;

    protected $fillable = ['standardId', 'name', 'elementName','about'];

    public function standards()
    {
        return $this->belongsTo(QipStandard::class, 'standardId');
    }
}
