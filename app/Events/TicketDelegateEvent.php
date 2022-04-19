<?php

namespace App\Events;

use App\Ticket;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketDelegateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $ticket, $delegator;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, User $user, User $delegator)
    {
        $this->user = $user;
        $this->delegator = $delegator;
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('tickets-actions-channel');
    }

    public function broadcastWith()
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_uuid' => $this->ticket->uuid,
            'owner_id' => $this->ticket->user->id,
            'user_id' => $this->user->id,
            'delegator' => $this->delegator->getName(),
            'user_name' => $this->user->first_name . " " . $this->user->last_name,
        ];
    }
}
