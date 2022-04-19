<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPasswordUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $temporary_password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($temporary_password, $user)
    {
        $this->user = $user;
        $this->temporary_password = $temporary_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user-password-update')->with(['user' => $this->user, 'temporary_password' => $this->temporary_password]);
    }
}
