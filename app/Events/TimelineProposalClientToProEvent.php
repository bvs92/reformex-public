<?php

namespace App\Events;

use App\Timeline;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimelineProposalClientToProEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timeline;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timeline $timeline)
    {
        $this->timeline = $timeline;
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
            // 'user' => $this->user->getTheName(), // eroare?
            // 'subject' => $this->ticket->subject
        ];
    }
}
