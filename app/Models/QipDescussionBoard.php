<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QipDescussionBoard extends Model
{
    protected $table = 'qip_discussion_board';
    // public $timestamps = false;

    protected $fillable = ['qipid', 'areaid', 'commentText','added_by'];


    public function user() {
        return $this->belongsTo(User::class, 'added_by');
    }


}
