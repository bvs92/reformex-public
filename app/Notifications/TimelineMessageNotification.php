<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TimelineMessageNotification extends Notification
{
    use Queueable;

    public $timeline;
    public $owner;
    public $type, $the_response_message;

    // type: client sau pro

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($timeline, $owner, $the_response_message, $type)
    {
        $this->timeline = $timeline;
        $this->owner = $owner;
        $this->type = $type;
        $this->the_response_message = $the_response_message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail'];
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'timeline_id' => $this->timeline->id,
            'type' => $this->type,
            // 'user_id'   => auth()->user()->id, // demand owner
            'user_id'   => $this->owner->id,
            // 'user_id'   => $this->timeline->professional->user->id,
            'response_timeline' => $this->the_response_message,
            'subject'   => 'Mesaj trimis'
        ];
    }
}
