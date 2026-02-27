<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAttachment extends Model
{
    protected $fillable = [
        'email_id',
        'name',
        'path',
        'size',
        'mime'
    ];

    public function email()
    {
        return $this->belongsTo(EmailLog::class, 'email_id');
    }
}
