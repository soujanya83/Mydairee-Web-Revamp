<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNote extends Model
{
    protected $table = 'app_notes';

    protected $fillable = [
        'centerid',
        'created_by',
        'title',
        'content',
    ];
}