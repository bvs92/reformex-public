<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketMessageInactiveUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Mesaj tichet #' . $this->ticket->uuid)
            ->line('Ai primit un mesaj nou la tichetul #' . $this->ticket->uuid)
            ->action('Vezi mesaj', route('tickets.details.uuid', $this->ticket->uuid));

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_uuid' => $this->ticket->uuid,
            'user_id' => auth()->user()->id,
            'subject_tichet' => $this->ticket->subject,
            'subject' => 'Mesaj tichet',
        ];
    }
}
