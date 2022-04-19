<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminChangeUserAccount extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $status)
    {
        $this->user = $user;
        $this->status = $status;
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
        $name = $this->user->last_name . " " . $this->user->first_name;

        if ($this->status) {
            return (new MailMessage)
                ->subject('Cont activat')
                ->greeting('Salutare, ' . $name . '.')
                ->line('Felicitări! Contul tău a fost activat. De acum poți accesa platforma REFORMEX pentru a găsi noi proiecte din domeniul construcțiilor.')
                ->action('Accesează platforma', url('home'));
        } else {
            return (new MailMessage)
                ->subject('Cont dezactivat')
                ->greeting('Salutare, ' . $name)
                ->line('Ne pare rău! Contul tău a fost dezactivat. Pentru a afla mai multe detalii, trimite un e-mail către suport@reformex.ro')
                ->line('Mulțumim pentru înțelegere!');
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
