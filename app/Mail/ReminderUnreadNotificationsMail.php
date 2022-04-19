<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReminderUnreadNotificationsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public $user, $notifications;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notifications, User $user)
    {
        $this->user = $user;
        $this->notifications = $notifications;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.unread-notifications')->with(['notifications' => $this->notifications, 'user' => $this->user]);
    }
}
