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

    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'title'   => 'New Announcement',
    //         'message' => $this->announcement->title,
    //         'url' => 'https://mydiaree.com.au/events/' . $this->announcement->id,
    //         'icon'    => 'fa fa-bullhorn',
    //     ];
    // }

    public function toDatabase($notifiable)
    {
        $isEvent = strtolower($this->announcement->type) === 'events';

        return [
            'title'   => $isEvent ? 'New Event' : 'New Announcement',
            'message' => $this->announcement->title,
            'url'     => $isEvent
                ? '/events/' . $this->announcement->id
                : '/events/' . $this->announcement->id,
            'icon'    => $isEvent
                ? 'fa fa-calendar'
                : 'fa fa-bullhorn',
        ];
    }
}
