<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BannerActivationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $banner;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($banner)
    {
        $this->banner = $banner;
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
        if ($this->banner->user) {
            $has_user = true;
            $name = $this->banner->user->last_name . " " . $this->banner->user->first_name;
        }

        $status = $this->banner->status == 1 ? 'activat' : 'respins';

        if ($this->banner->status == 1) {
            return (new MailMessage)
                ->subject('Anunț banner ' . $status)
                ->greeting('Salutare, ' . $has_user ? $name : '')
                ->line('Anunțul cu numărul #' . $this->banner->uuid . ' a fost ' . $status . '.');
        } else {
            return (new MailMessage)
                ->subject('Anunț banner ' . $status)
                ->greeting('Salutare, ' . $has_user ? $name : '')
                ->line('Anunțul cu numărul #' . $this->banner->uuid . ' a fost ' . $status . '.')
                ->line('Te rugăm să verifici și să corectezi informațiile despre anunț.')
                ->action('Vezi anunț', route('advertising.banners.personal.show', $this->banner->uuid));
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
