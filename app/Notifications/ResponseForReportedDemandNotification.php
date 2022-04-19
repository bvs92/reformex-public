<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponseForReportedDemandNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $demand, $user, $status, $report;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($demand, $report, $user, $status)
    {
        $this->demand = $demand;
        $this->user = $user;
        $this->status = $status;
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $_channels = ['database'];

        // if (!$notifiable->notification_settings) {
        //     return $_channels;
        // }

        // if ($notifiable->notification_settings->isEmailActive()) {
        //     array_push($_channels, 'mail');
        // }

        // if ($notifiable->notification_settings->isPhoneActive()) {
        //     // array_push($_channels, 'nexmo'); ??
        // }

        return $_channels;

        // return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $status = $this->status == 'is_true' ? 'Validă' : 'Invalidă';
        $demand_uuid = $this->demand->hasUUID() ? $this->demand->uuid : $this->demand->id;

        return (new MailMessage)
            ->subject('Răspuns cerere raportată')
            ->line('Salutare, ' . $this->user->getName() . '.')
            ->line('Ai primit un răspuns pentru raportarea aferentă cererii #' . $demand_uuid)
            ->action('Vezi răspuns', route('demands_reports.show' . $this->report->id))
            ->line('Cererea a fost marcată drept ' . $status)
            ->line('Îți mulțumim pentru implicare!');
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
            'demand_id' => $this->demand->id,
            'demand_uuid' => $this->demand->uuid,
            'user_id' => $this->user->id,
            'status' => $this->status,
            'subject' => 'Răspuns cerere raportată',
        ];
    }
}
