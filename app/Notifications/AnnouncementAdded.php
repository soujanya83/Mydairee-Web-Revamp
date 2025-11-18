<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementAdded extends Notification
{
    public $announcement;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        return ['database']; // or add 'mail', 'broadcast' if needed
    }

    public function toDatabase($notifiable)
    {
        return [
            'title'   => 'New Announcement',
            'message' => $this->announcement->title,
            'url'     => route('announcements.view', $this->announcement->id), // adjust as needed
            'icon'    => 'fa fa-bullhorn',
        ];
    }
}
