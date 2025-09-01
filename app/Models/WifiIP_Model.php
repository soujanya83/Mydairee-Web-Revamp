<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WifiIP_Model extends Model
{
        protected $table = 'wifi_ipaddress';
    // public $timestamps = false;

    protected $fillable = [
        'wifi_ip',
        'wifi_address',
        'wifi_name',
        'status',
    ];
}
