<?php

namespace App\Notifications\companies;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MarkCompanyAsUnverified extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
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
        $name = $this->user->last_name . " " . $this->user->first_name;

        return (new MailMessage)
            ->subject('Verificare firmă eșuată')
            ->greeting('Salutare, ' . $name)
            ->line('În urma verificării datelor firmei, am constatat că acestea nu sunt valide.')
            // ->action('Notification Action', url('/'))
            ->line('Pentru a remedia acest lucru, îți recomandăm să corectezi informațiile și să le retrimiți spre verificare.')
            ->line('Doar astfel vei primi insigna de „Verificat”, care va fi vizibilă în profilul tău public.')
            ->action('Retrimite informații', route('home'));
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
