<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Childparent extends Model
{
    protected $table = 'childparent';
    public $timestamps = false;

    protected $fillable = [
        'childid', 'parentid', 'relation'
    ];

    public function child() {
        return $this->belongsTo(Child::class, 'childid');
    }
}
