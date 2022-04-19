<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminProCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $password, $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
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
            ->subject('Cont de profesionist creat')
            ->line('Contul tău de profesionist a fost creat cu succes.')
            ->line('Autentifică-te în cont folosind următoarele date: ')
            ->line('E-mail: ' . $this->email)
            ->line('Parolă: ' . $this->password)
            ->line('Îți recomandăm să schimbi parola după prima autentificare.')
            ->line('Accesează platforma REFORMEX.')
            ->action('Autentificare', route('login'));
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
