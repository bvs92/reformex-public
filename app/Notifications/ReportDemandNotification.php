<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportDemandNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $reporting_user, $demand;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reporting_user, $demand)
    {
        $this->reporting_user = $reporting_user;
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
        return ['database'];

        // $_channels = ['database'];

        // if (!$notifiable->notification_settings) {
        //     return $_channels;
        // }

        // if ($notifiable->notification_settings->isEmailActive()) {
        //     array_push($_channels, 'mail');
        // }

        // if ($notifiable->notification_settings->isPhoneActive()) {
        //     // array_push($_channels, 'nexmo'); ??
        // }

        // return $_channels;

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $report = $this->reporting_user->reports()->where('demand_id', $this->demand->id)->first();
        $demand_uuid = $this->demand->hasUUID() ? $this->demand->uuid : $this->demand->id;
        return (new MailMessage)
            ->subject('Cerere raportată')
            ->line('Cererea cu numărul #' . $demand_uuid . ' a fost raportată de către utilizatorul ' . $this->reporting_user->professional->getName())
            // ->action('Vezi raportarea', route('demands_reports.show' . $report->id))
            ->line('Îți mulțumim pentru implicare.');
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
            'user_id' => $this->reporting_user->id,
            'subject' => 'Cerere raportată',
        ];
    }
}
