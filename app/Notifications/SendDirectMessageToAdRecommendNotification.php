<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendDirectMessageToAdRecommendNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
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
            ->replyTo($this->details['email'], $this->details['name'])
            ->subject('Ai primit un mesaj direct de la - ' . $this->details['name'])
            ->greeting('Salutare!')
            ->greeting($this->details['name'] . ' ți-a trimis un mesaj prin formularul atașat anunțului de firmă recomandată.')
            ->line('Nume: ' . $this->details['name'])
            ->line('Număr telefon: ' . $this->details['phone'])
            ->line('Adresă email: ' . $this->details['email'])
            ->line('Oraș / Cod poștal: ' . $this->details['city'])
            ->line('Mesaj')
            ->line($this->details['message']);
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
            //
        ];
    }
}
