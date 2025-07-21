<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ObservationAdded extends Notification
{
    use Queueable;

    public $observation;

    public function __construct($observation)
    {
        $this->observation = $observation;
    }

    public function via($notifiable)
    {
        return ['database']; // You can also add 'mail', 'broadcast', etc.
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Observation Added',
            'message' => 'An observation titled "' . $this->observation->title . '" was added.',
            'observation_id' => $this->observation->id,
        ];
    }
}
