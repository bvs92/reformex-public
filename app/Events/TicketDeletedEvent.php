<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket_uuid, $ticket_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticket_id, $ticket_uuid)
    {
        $this->ticket_uuid = $ticket_uuid;
        $this->ticket_id = $ticket_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user-tickets-messages-channel.' . $this->ticket_id);
    }

    public function broadcastWith()
    {
        return [
            'ticket_uuid' => $this->ticket_uuid,
            'ticket_id' => $this->ticket_id,
            // 'status' => $this->user->getTheName(), // eroare?
            // 'subject' => $this->ticket->subject
        ];
    }
}
