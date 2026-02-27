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
        'sent_at',
       
    ];

    protected $casts = [
        'sent_at' => 'datetime',
       
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function attachmentsRelation()
    {
        return $this->hasMany(\App\Models\EmailAttachment::class, 'email_id');
    }

    public function childrenRelation()
    {
        return $this->belongsToMany(\App\Models\Child::class, 'email_child', 'email_id', 'child_id')->withTimestamps();
    }
}
