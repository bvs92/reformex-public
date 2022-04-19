<?php

namespace App\Notifications\demands;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandReportNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $pros, $demand;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($pros, $demand)
    {
        $this->pros = $pros;
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
        $total = '';

        if ($this->pros) {
            $numItems = count($this->pros);
            $i = 0;
            foreach ($this->pros as $key => $pro) {
                $total .= $pro->getTheName();

                if (++$i === $numItems) {
                    $total .= '.';
                } else {
                    $total .= ', ';
                }
            }
        }

        $the_route = route('public.demands.single', ['uuid' => $this->demand->uuid, 'unique' => $this->demand->detail->unique]);

        return (new MailMessage)
            ->subject('Cerere #' . $this->demand->uuid . ' dezactivată')
            ->greeting('Cererea ta a fost dezactivată.')
            ->line('Profesioniștii care ți-au vizitat cererea sunt urmatorii: ' . $total)
            ->line('Încă nu ai reușit să găsești un profesionist?')
            ->line('Poți retrimite oricând cererea pentru a primi și alte oferte.')
            ->action('Relansează cerere.', $the_route);
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
