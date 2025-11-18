<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildStatusHistory extends Model
{
    protected $table = "child_status_history";
    public $timestamps = false;

    // public $timestamps = false;
    protected $fillable = [
        'child_id',
        'user_id',
        'new_status',
        'old_status',
        'created_at',
        'updated_at',
        'date_time',
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
