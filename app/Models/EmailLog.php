<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'parent_id',
        'parent_email',
        'parent_name',
        'sent_by',
        'subject',
        'message',
        'attachments',
        'children',
        'sent_at'
    ];

    protected $casts = [
        'attachments' => 'array',
        'children' => 'array',
        'sent_at' => 'datetime'
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
