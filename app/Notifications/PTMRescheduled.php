<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PTMRescheduled extends Notification
{
    use Queueable;

    public $ptm;
    public $reschedule;

    public function __construct($ptm, $reschedule)
    {
        $this->ptm = $ptm;
        $this->reschedule = $reschedule;
    }

    public function via($notifiable)
    {
        if ($notifiable->userType === 'Parent') 
        {
             return ['mail', 'database'];
        }
        else{
             return ['database'];

        }
       
    }

    // public function toMail($notifiable)
    // {
    //     // ✅ Fix field names
    //     $newDate = $this->reschedule->ptmdate ?? null;
    //     $newSlot = $this->reschedule->newslot ?? null;

    //     $formattedDate = $newDate
    //         ? \Carbon\Carbon::parse($newDate)->format('d M Y')
    //         : 'N/A';

    //     return (new MailMessage)
    //         ->subject('PTM Rescheduled')
    //         ->greeting('Hello ' . $notifiable->name . ',')
    //         ->line('Your PTM has been rescheduled.')
    //         ->line('📅 New Date: ' . $formattedDate)
    //         ->line('⏰ New Slot: ' . ($newSlot ?? 'N/A'))
    //         ->line('📝 PTM Title: ' . ($this->ptm->title ?? 'N/A'))
    //         ->line('🎯 Objective: ' . ($this->ptm->objective ?? 'N/A'))
    //         ->action('View Details', route('ptm.viewptm', $this->ptm->id))
    //         ->line('Please make note of the new schedule.')
    //         ->salutation('Regards,')
    //         ->line('Thank you for using our application!');
    // }

    public function toMail($notifiable)
    {
        $newDate = $this->reschedule->rescheduledate->date ?? null;
        $newSlot = $this->reschedule->rescheduleslot->slot ?? null;

        $formattedDate = $newDate
            ? \Carbon\Carbon::parse($newDate)->format('d M Y')
            : 'N/A';

        \Log::info('📧 Sending PTM Reschedule Mail', [
            'to' => $notifiable->email,
            'date' => $newDate,
            'slot' => $newSlot,
        ]);

        return (new MailMessage)
            ->subject('PTM Rescheduled Successfully')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your PTM has been rescheduled.')
            ->line('📅 New Date: ' . $formattedDate)
            ->line('⏰ New Slot: ' . ($newSlot ?? 'N/A'))
            ->line('📝 PTM Title: ' . ($this->ptm->title ?? 'N/A'))
            ->line('🎯 Objective: ' . ($this->ptm->objective ?? 'N/A'))
            ->action('View Details', route('ptm.viewptm', $this->ptm->id))
            ->line('Please make note of the new schedule.')
            ->salutation('Regards,')
            ->line('Thank you for using our application!');
    }



    public function toDatabase($notifiable)
    {
            $date = $this->reschedule->rescheduledate->date ?? null; 
    $formattedDate = $date ? \Carbon\Carbon::parse($date)->format('d M Y') : 'N/A';
     $schedulerName = \Illuminate\Support\Facades\Auth::user()->name ?? 'Administrator';

        return [
            'ptm_id' => $this->ptm->id,
            'url' => route('ptm.viewptm', $this->ptm->id),
            // 'title' => $this->ptm->title,
            'title' => 'The PTM "' . $this->ptm->title . '" has been rescheduled by ' 
            . $schedulerName . ' to ' . $formattedDate . '.',
        ];
        
        \Log::info('📧 noti details', [
            'to' => $notifiable->email,
            'date' =>  $formattedDate,
            'slot' => $schedulerName,
        ]);
    }
}
