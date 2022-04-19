<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdRecommendActivationNotification extends Notification implements ShouldQueue
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
        $has_user = false;
        if ($this->ad->user) {
            $has_user = true;
            $name = $this->ad->user->last_name . " " . $this->ad->user->first_name;
        }

        $status = $this->ad->status == 1 ? 'activat' : 'respins';

        if ($this->ad->status == 1) {
            return (new MailMessage)
                ->subject('Anunț firmă recomandată ' . $status)
                ->greeting('Salutare, ' . $has_user ? $name : '')
                ->line('Anunțul cu numărul #' . $this->ad->uuid . ' a fost ' . $status . '.');
        } else {
            return (new MailMessage)
                ->subject('Anunț firmă recomandată ' . $status)
                ->greeting('Salutare, ' . $has_user ? $name : '')
                ->line('Anunțul cu numărul #' . $this->ad->uuid . ' a fost ' . $status . '.')
                ->line('Te rugăm să verifici și să corectezi informațiile despre anunț.')
                ->action('Vezi anunț', route('advertising.ad_recommend.personal.show', $this->ad->uuid));
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
