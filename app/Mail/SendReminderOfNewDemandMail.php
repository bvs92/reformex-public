<?php

namespace App\Mail;

use App\Utility\DictionaryRegex;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReminderOfNewDemandMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $demand;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $demand)
    {
        $this->user = $user;
        $this->demand = $demand;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->demand->subject = DictionaryRegex::mask($this->demand->subject);

        return $this->subject("Cerere nouă disponibilă")->view('emails.new-demand')->with(['demand' => $this->demand, 'user' => $this->user]);
    }
}
