<?php

namespace App\Notifications\demands;

use App\Demand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandSentNotification extends Notification implements ShouldQueue
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
            ->subject('Cerere trimisă cu succes')
            ->greeting('Felicitări! Cererea a fost înregistrată cu succes.')
            ->line('Profesioniștii înregistrați în platformă vor putea acum să exploreze cererea și să te contacteze.')
            ->action('Accesează cerere', $the_route)
            ->line('Prin apăsarea butonului „Accesează cerere”, poți vedea atât firmele care ți-au văzut proiectul, cât și să elimini cererea din platformă.')
            ->line('Îți recomandăm să verifici profilul fiecărei firme care te contactează. Noi îți punem la dispoziție cei mai buni profesioniști din domeniu, dar decizia, în cele din urmă, îți aparține.')
            ->line('Spor în contruirea viziunii tale în realitate!');
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
