<?php

namespace App\Notifications;

use App\Ticket;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketMessageToResolverNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, User $user)
    {
        $this->ticket = $ticket;
        $this->user = $user;
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
            ->subject('Mesaj tichet ' . $this->ticket->uuid)
            ->line('Utilizatorul ' . $this->user->getName() . ' a lăsat un nou mesaj la tichetul #' . $this->ticket->uuid)
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
