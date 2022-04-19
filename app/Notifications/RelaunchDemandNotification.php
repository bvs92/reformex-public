<?php

namespace App\Notifications;

use App\Demand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RelaunchDemandNotification extends Notification implements ShouldQueue
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
        $the_route = route('public.demands.single', ['uuid' => $this->demand->uuid, 'unique' => $this->demand->detail->unique]);

        return (new MailMessage)
            ->subject('Cerere #' . $this->demand->uuid . ' relansată')
            ->greeting('Salutare,')
            ->line('Cererea cu numărul #' . $this->demand->uuid . ' a fost relansată.')
            ->line('Este permis unui număr de ' . $this->demand->detail->offers . ' firme să te contacteze.')
            ->action('Detalii cerere', $the_route);
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
