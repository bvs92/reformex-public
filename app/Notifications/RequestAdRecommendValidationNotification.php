<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestAdRecommendValidationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ad;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ad)
    {
        $this->ad = $ad;
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
        $name = $this->ad->user->last_name . " " . $this->ad->user->first_name;

        return (new MailMessage)
            ->subject('Solicitare validare anunț firmă recomandată')
            ->greeting('Utilizatorul ' . $name . ', email: ' . $this->ad->user->email)
            ->line('A solicitat revalidarea anunțului #' . $this->ad->uuid)
            ->action('Vezi anunț', route('advertising.admin.ad_recommend.show', $this->ad->uuid));
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
