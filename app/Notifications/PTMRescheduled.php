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
    public $actorType;
    public $isBulk = false;
    public $bulkPayload = null;

    public function __construct($ptm, $reschedule, $actorType = null)
    {
        $this->ptm = $ptm;
        $this->actorType = $actorType; // optional info about who triggered the reschedule

        // Support two modes:
        // 1) Single reschedule: $reschedule is a PTMReschedule model
        // 2) Bulk reschedule: $reschedule is an array payload with keys: children (array of names), date (Y-m-d), slot (string), actorName (string)
        if (is_array($reschedule) || $reschedule instanceof \Illuminate\Support\Collection) {
            $this->isBulk = true;
            $this->bulkPayload = is_array($reschedule) ? $reschedule : $reschedule->toArray();
            $this->reschedule = null;
        } else {
            $this->reschedule = $reschedule;
        }
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

    

    public function toMail($notifiable)
    {
        // Single reschedule
        if (!$this->isBulk) {
            $newDate = $this->reschedule->rescheduledate->date ?? null;
            $newSlot = $this->reschedule->rescheduleslot->slot ?? null;

            $formattedDate = $newDate
                ? \Carbon\Carbon::parse($newDate)->format('d M Y')
                : 'N/A';

           

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

        // Bulk reschedule email (staff receiving summary)
        $children = $this->bulkPayload['children'] ?? [];
        $date = $this->bulkPayload['date'] ?? null;
        $slot = $this->bulkPayload['slot'] ?? null;
        $actorName = $this->bulkPayload['actorName'] ?? ($this->actorType ?? 'Staff');

        $formattedDate = $date ? \Carbon\Carbon::parse($date)->format('d M Y') : 'N/A';
        $childList = is_array($children) ? implode(', ', $children) : (string) $children;

       
        return (new MailMessage)
            ->subject('PTM Bulk Rescheduled')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A bulk reschedule has been applied for the PTM: ' . ($this->ptm->title ?? 'N/A'))
            ->line('📅 New Date: ' . $formattedDate)
            ->line('⏰ New Slot: ' . ($slot ?? 'N/A'))
            ->line('👥 Affected children: ' . ($childList ?: 'N/A'))
            ->line('Changed by: ' . $actorName)
            ->action('View Details', route('ptm.viewptm', $this->ptm->id))
            ->line('Please make note of the new schedule.')
            ->salutation('Regards,');
    }



    public function toDatabase($notifiable)
    {
        if (!$this->isBulk) {
            $date = $this->reschedule->rescheduledate->date ?? null;
            $formattedDate = $date ? \Carbon\Carbon::parse($date)->format('d M Y') : 'N/A';
            $schedulerName = \Illuminate\Support\Facades\Auth::user()->name ?? 'Administrator';

           

            return [
                'ptm_id' => $this->ptm->id,
                'url' => route('ptm.viewptm', $this->ptm->id),
                'title' => 'The PTM "' . $this->ptm->title . '" has been rescheduled by ' 
                    . $schedulerName . ' to ' . $formattedDate . '.',
            ];
        }

        // Bulk payload
        $children = $this->bulkPayload['children'] ?? [];
        $date = $this->bulkPayload['date'] ?? null;
        $slot = $this->bulkPayload['slot'] ?? null;
        $actorName = $this->bulkPayload['actorName'] ?? ($this->actorType ?? 'Staff');

        $formattedDate = $date ? \Carbon\Carbon::parse($date)->format('d M Y') : 'N/A';
        $childList = is_array($children) ? implode(', ', $children) : (string) $children;

        return [
            'ptm_id' => $this->ptm->id,
            'url' => route('ptm.viewptm', $this->ptm->id),
            'title' => 'The PTM "' . $this->ptm->title . '" has been bulk-rescheduled by ' . $actorName . ' to ' . $formattedDate . '.',
            'children' => $children,
            'children_list' => $childList,
            'slot' => $slot,
        ];
    }
}
