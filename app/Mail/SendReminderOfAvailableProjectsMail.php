<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReminderOfAvailableProjectsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $projects, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $projects)
    {
        $this->projects = $projects;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Avem proiecte noi disponibile pentru tine")->view('emails.contact-me')->with(['projects' => $this->projects, 'user' => $this->user]);
    }
}
