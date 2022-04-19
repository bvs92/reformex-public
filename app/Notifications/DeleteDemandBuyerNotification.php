<?php

namespace App\Notifications;

use App\Demand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeleteDemandBuyerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $demand;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Demand $demand)
    {
        $this->demand = $demand;
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
            ->subject('Eliminare din lista ofertanților #' . $this->demand->uuid)
            ->greeting('Salutare,')
            ->line('Deblocarea cererii #' . $this->demand->uuid . ' a fost eliminată.')
            ->line('Suma folosită pentru deblocarea cererii ți-a fost restituită.')
            ->line('Mulțumim pentru înțelegere!');
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
