<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketSingleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticket_id)
    {
        $this->ticket_id = $ticket_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('tickets-channel.' . $this->ticket_id);
    }

    public function broadcastWith()
    {
        return [
            'ticket_id' => $this->ticket_id,
            // 'user' => $this->user->getTheName(), // eroare?
            // 'subject' => $this->ticket->subject
        ];
    }
}
