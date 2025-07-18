<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipStandard extends Model
{
    protected $table = 'qip_standards';
    public $timestamps = false;

    protected $fillable = ['areaId', 'name', 'about'];

    public function elements()
{
    return $this->hasMany(QipElement::class, 'standardId');
}



}
