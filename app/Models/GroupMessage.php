<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;

    protected $table = 'group_messages';

    protected $fillable = [
        'centerid', 'sender_id', 'body'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
