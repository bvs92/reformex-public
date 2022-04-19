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

class ClientProposalEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timeline, $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timeline $timeline, User $user)
    {
        $this->timeline = $timeline;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('client-timelines-channel.' . $this->timeline->id);
        // return new PrivateChannel('timelines-channel');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->timeline->id,
            'uuid' => $this->timeline->uuid,
            'user' => $this->user->getTheName(), // eroare?
            // 'subject' => $this->ticket->subject
        ];
    }
}
