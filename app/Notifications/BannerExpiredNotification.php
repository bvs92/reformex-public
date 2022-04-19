<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BannerExpiredNotification extends Notification implements ShouldQueue
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
        $name = $this->banner->user->last_name . " " . $this->banner->user->first_name;

        return (new MailMessage)
            ->subject('Anunțul tău banner a expirat')
            ->greeting('Salutare,  ' . $name)
            ->line('Anunțul cu numărul #' . $this->banner->uuid . ' a expirat.')
            ->line('Poți reactiva anunțul oricând. Vizitează mai jos pagina anunțului.')
            ->action('Vezi anunț', route('advertising.banners.personal.show', $this->banner->uuid));
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
