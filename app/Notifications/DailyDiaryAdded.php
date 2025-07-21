<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DailyDiaryAdded extends Notification
{
    use Queueable;

    public $diary;

    public function __construct($diary)
    {
        $this->diary = $diary;
    }

    public function via($notifiable)
    {
        return ['database']; // Or add 'mail', 'broadcast' if needed
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Daily Diary Entry',
            'message' => 'A new diary entry was added for ' . $this->diary->date,
            'diary_id' => $this->diary->id,
        ];
    }
}
