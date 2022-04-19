<?php

namespace App\Events;

use App\User;
use App\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TicketEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
        // $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('tickets-channel');
    }

    public function broadcastAs()
    {
        return 'ticket-event';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->ticket->id,
            'uuid' => $this->ticket->uuid,
            'user' => $this->ticket->user->getName(),
            'subject' => $this->ticket->subject
        ];
    }
}
