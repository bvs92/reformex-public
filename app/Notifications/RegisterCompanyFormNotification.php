<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterCompanyFormNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
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
            ->subject('Înregistrare firmă - ' . $this->details['company_name'])
            ->greeting('Compania ' . $this->details['company_name'] . ' a trimis o cerere de înregistrare.')
            ->line('Nume proprietar: ' . $this->details['owner_name'])
            ->line('Număr telefon: ' . $this->details['phone'])
            ->line('Adresă email: ' . $this->details['email'])
            ->line('CUI: ' . $this->details['cui'])
            ->line('Număr înregistrare: ' . $this->details['register_number'])
            ->line('Adresă firmă: ' . $this->details['address']);
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
