<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QiplElementsComments extends Model
{
    protected $table = 'qip_elements_comments';
    // public $timestamps = false;

    protected $fillable = ['qipid', 'element_id', 'commentText','added_by'];
}
