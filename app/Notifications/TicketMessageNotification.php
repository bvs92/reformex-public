<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $response;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket, $response)
    {
        $this->ticket = $ticket;
        $this->response = $response;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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
            ->subject('Mesaj tichet ' . $this->ticket->uuid)
            ->line('Ai primit un mesaj nou la tichetul #' . $this->ticket->uuid)
            ->action('Vezi mesaj', route('tickets.show.vue.uuid', $this->ticket->uuid));
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
            'user_id' => auth()->user()->id,
            'response_ticket' => $this->response->message,
            'subject_tichet' => $this->ticket->subject,
            'subject' => 'Mesaj tichet',
        ];
    }
}
