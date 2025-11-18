<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class contact extends Model
{
      protected $table = "contact_us";

    protected $fillable = [
        'name', 'email', 'phone', 'message', 'consent'
    ];
}
