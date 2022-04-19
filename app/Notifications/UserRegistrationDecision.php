<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistrationDecision extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type)
    {
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
        if ($this->type == 'refuse') {
            return (new MailMessage)
                ->subject('Înregistrare eșuată')
                ->line('Cererea ta a fost verificată de către un moderator, însă a fost refuzată. Intră în platformă pentru a corecta informațiile și a retrimite cererea de înregistrare.')
                ->action('Vezi detalii', route('home'));
        } else {
            return (new MailMessage)
                ->subject('Înregistrare cu succes')
                ->line('Cererea ta a fost verificată cu succes de către un moderator.')
                ->line('De acum te poți autentifica și aplica pentru proiecte.')
                ->action('Vezi detalii', route('home'));
        }

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
