<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketNotification extends Notification
{
    use Queueable;

    public $ticket;
    public $type;

    /**
     * Type: ticket_created, ticket_status_changed, ticket_deleted
     */

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket, $type)
    {
        $this->ticket = $ticket;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail'];
        // return ['database'];
        return $_channels = ['database', 'mail'];

        if (!$notifiable->notification_settings) {
            return $_channels;
        }

        if ($notifiable->notification_settings->isEmailActive()) {
            array_push($_channels, 'mail');
        }

        if ($notifiable->notification_settings->isPhoneActive()) {
            // array_push($_channels, 'nexmo'); ??
        }

        return $_channels;
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
            ->subject('Tichet nou creat')
            ->line('A fost creat un nou tichet.')
            ->action('Verifică tichet', route('tickets.show.uuid', $this->ticket->uuid));
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
            'ticket_status' => $this->ticket->status,
            'type' => $this->type,
            'user_id' => auth()->user()->id,
            'subject' => 'Notificare tichet',
        ];
    }
}
