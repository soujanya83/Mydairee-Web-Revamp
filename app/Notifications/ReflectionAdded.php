<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReflectionAdded extends Notification
{
    use Queueable;

    public $reflection;

    public function __construct($reflection)
    {
        $this->reflection = $reflection;
    }

    public function via($notifiable)
    {
        return ['database']; // Also use 'mail' or 'broadcast' if needed
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Reflection Added',
            'message' => 'A new reflection titled "' . $this->reflection->title . '" was added.',
            'reflection_id' => $this->reflection->id,
        ];
    }
}

