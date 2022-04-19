<?php

namespace App\Notifications;

use App\Coupon;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendCouponToUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $coupon, $by_email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Coupon $coupon, $by_email)
    {
        $this->coupon = $coupon;
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
        $the_route = route('coupons.show.pro', $this->coupon->uuid);

        return (new MailMessage)
            ->subject('Cupon nou')
            ->line('Salutare, ' . $name . '.')
            ->line('Felicitări! Ai primit un cupon în valoare de ' . $this->coupon->amount / 100 . ' RON.')
            ->line('Activează cuponul pentru a folosi suma primită.')
            ->action('Aplică cupon', $the_route);
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
            'coupon_code' => $this->coupon->code,
            'amount' => $this->coupon->amount / 100,
        ];
    }
}
