<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdRecommendPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ad, $payment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ad, $payment)
    {
        $this->ad = $ad;
        $this->payment = $payment;
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
        return (new MailMessage)
            ->subject('Plată anunț firmă recomandată')
            ->line('A fost înregistrată o plată pentru anunț firmă recomandată cu numarul #' . $this->ad->uuid)
            ->line('ID plată: ' . $this->payment->uuid)
            ->line('Dată plată: ' . formatCarbonDate(Carbon::parse($this->payment->created_at)))
            ->line('E-mail utilizator: ' . $this->ad->user->email)
            ->line('Total plata: ' . $this->payment->amount_total / 100 . ' RON.');
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
