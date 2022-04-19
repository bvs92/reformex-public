<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponseCouponRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $type, $by_email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $type, $by_email)
    {
        $this->type = $type;
        $this->user = $user;
        $this->by_email = $by_email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $__channels = ['database'];

        if ($this->by_email) {
            array_push($__channels, 'mail');
        }
        return $__channels;
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

        if ($this->type == 'accept') {
            return (new MailMessage)
                ->subject('Solicitare cupon acceptată')
                ->line('Salutare, ' . $name)
                ->line('Solicitarea cuponului a fost acceptată.');
        } else {
            return (new MailMessage)
                ->subject('Solicitare cupon respinsă')
                ->line('Salutare, ' . $name)
                ->line('Solicitarea cuponului a fost respinsă.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'type' => $this->type,
        ];
    }
}
