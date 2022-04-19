<?php

namespace App\Events;

use App\User;
use App\Winner;
use App\Timeline;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WinnerDemandEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timeline, $user, $winner;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timeline $timeline, User $user, Winner $winner)
    {
        $this->timeline = $timeline;
        $this->user = $user;
        $this->winner = $winner;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('client-timelines-channel.' . $this->timeline->id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->timeline->id,
            'uuid' => $this->timeline->uuid,
            'user' => $this->user->getTheName(), // eroare?
            'winner' => $this->winner
        ];
    }
}
