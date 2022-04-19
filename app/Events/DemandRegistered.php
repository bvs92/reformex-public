<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DemandRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $demand;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($demand)
    {
        // $this->user = $user;
        $this->demand = $demand;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
    }
}
