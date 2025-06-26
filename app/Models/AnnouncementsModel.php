<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Child;

class AnnouncementsModel extends Model
{
    protected $table = "announcement";
    public $timestamps = false;
    protected $fillable = [
'title',
'text',
'eventDate',
'status',
'centerid',
'createdBy',
'createdAt',
'announcementMedia'
    ];

public function children()
{
    return $this->belongsToMany(Child::class, 'announcementchild', 'aid', 'childid');
}

public function creator()
{
    return $this->belongsTo(User::class, 'createdBy', 'userid');
}
}
