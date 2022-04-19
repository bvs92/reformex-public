<?php

namespace App\Events;

use App\Timeline;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimelineCancelWinner implements ShouldBroadcast
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
    }

    public function broadcastWith()
    {
        return [
            'timeline_id' => $this->timeline->id,
            'user' => $this->user,
            // 'subject' => $this->ticket->subject
        ];
    }
}
