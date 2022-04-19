<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestBannerValidationNotification extends Notification implements ShouldQueue
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
            ->subject('Solicitare validare anunț banner')
            ->greeting('Utilizatorul ' . $name . ', email: ' . $this->banner->user->email)
            ->line('A solicitat revalidarea anunțului #' . $this->banner->uuid)
            ->action('Vezi anunț', route('advertising.banners.show', $this->banner->uuid));

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
