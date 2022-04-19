<?php

namespace App\Events;

use App\User;
use App\Demand;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BuyDemandEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $demand, $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Demand $demand, User $user)
    {
        $this->demand = $demand;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('demands-channel.' . $this->demand->id);
        // return new PrivateChannel('demands-channel');
    }

    // public function broadcastAs()
    // {
    //     return 'buy-demand-event';
    // }

    public function broadcastWith()
    {
        return [
            'id' => $this->demand->id,
            'uuid' => $this->demand->uuid,
            'user' => $this->user->getTheName(), // eroare?
            // 'subject' => $this->ticket->subject
        ];
    }


}
