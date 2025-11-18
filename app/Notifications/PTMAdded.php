<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PTMAdded extends Notification
{
    use Queueable;

    public $ptm;

    /**
     * Create a new notification instance.
     */
    public function __construct($ptm)
    {
        $this->ptm = $ptm;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

     /**
     * Get the mail representation of the notification.
     */
   public function toMail($notifiable)
    {
        // Get the earliest date (from relationship or accessor)
        $earliestDate = $this->ptm->ptm_dates_min_date 
            ?? $this->ptm->ptmDates()->min('date');

        // Format the earliest date nicely
        $formattedDate = $earliestDate 
            ? \Carbon\Carbon::parse($earliestDate)->format('d M Y') 
            : 'N/A';
          $slot = $this->ptm->ptmSlots->first()->slot ?? 'N/A';
        // Optional: log for debugging
        \Log::info('PTM Earliest Date:', [
            'earliest_date' => $earliestDate,
            'all_dates' => $this->ptm->ptmDates()->pluck('date')->toArray(),
        ]);

        // ✅ Return the actual email
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('New PTM Added')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new PTM has been scheduled.')
            ->line('📅 PTM Date: ' . $formattedDate)
            ->line('📝 PTM Title: ' . ($this->ptm->title ?? 'N/A'))
            ->line('🎯 Objective: ' . ($this->ptm->objective ?? 'N/A'))
            ->line('Be sure to be prepared for the meeting.')
            ->salutation('Regards,')
            ->line('Thank you for using our application!');
    }



    /**
     * Get the array representation of the notification for database.
     */
    public function toDatabase($notifiable)
    {
        return [

            'ptm_id' => $this->ptm->id,
            'url' => route('ptm.viewptm', $this->ptm->id),
            // 'title' => $this->ptm->title,
            'title' => 'A new PTM "' . $this->ptm->title . '" has been scheduled.',
        ];
    }
}
