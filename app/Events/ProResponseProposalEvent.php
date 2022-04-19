<?php

namespace App\Events;

use App\User;
use App\Timeline;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProResponseProposalEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timeline, $user, $response;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timeline $timeline, User $user, $response)
    {
        $this->timeline = $timeline;
        $this->user = $user;
        $this->response = $response;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('pro-timelines-channel.' . $this->timeline->id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->timeline->id,
            'uuid' => $this->timeline->uuid,
            'user' => $this->user->getTheName(), // eroare?
            'response' => $this->response == 'accept' ? 'ACCEPTAT' : 'RESPINS'
        ];
    }

}
