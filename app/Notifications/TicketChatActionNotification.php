<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketChatActionNotification extends Notification
{
    use Queueable;

    public $ticket, $type, $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket, $user, $type)
    {
        $this->ticket = $ticket;
        $this->type = $type;
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
        // return ['mail', 'database'];

        $_channels = ['database'];

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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
            // 'ticket_status' => $this->ticket->status,
            'type' => $this->type,
            'sender_id' => auth()->user()->id,
            'sender_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'user_id' => $this->user->id,
            'subject' => 'Invitație în conversație tichet',
        ];
    }
}
