<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminChangeUserProAccount extends Notification implements ShouldQueue
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
        return ['mail', 'database'];

        // $_channels = ['database'];

        // if (!$notifiable->notification_settings) {
        //     return $_channels;
        // }

        // if ($notifiable->notification_settings->isEmailActive()) {
        //     array_push($_channels, 'mail');
        // }

        // if ($notifiable->notification_settings->isPhoneActive()) {
        //     // array_push($_channels, 'nexmo'); ??
        // }

        // return $_channels;
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
                ->subject('Cont de profesionist activat')
                ->greeting('Salutare, ' . $name)
                ->line('Felicitări! Contul tău de profesionist a fost activat. De acum poți accesa platforma REFORMEX și debloca cererile lansate de către clienți.')
                ->action('Accesare platformă', route('home'));

        } else {
            return (new MailMessage)
                ->subject('Cont de profesionist dezactivat')
                ->greeting('Salutare, ' . $name)
                ->line('Ne pare rău! Contul tău de profesionist a fost dezactivat. Pentru detalii suplimentare, te rugăm sa ne trimiți un tichet.')
                ->action('Trimite tichet', route('tickets.create.new'))
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
            'status' => $this->status,
        ];
    }
}
