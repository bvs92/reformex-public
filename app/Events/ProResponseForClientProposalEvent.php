<?php

namespace App\Events;

use App\Prospect;
use App\Timeline;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProResponseForClientProposalEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $timeline, $prospect;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timeline $timeline, Prospect $prospect)
    {
        $this->timeline = $timeline;
        $this->prospect = $prospect;
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
            'timeline_id' => $this->timeline->id,
            'prospect_id' => $this->prospect->id, // eroare?
            'prospect_status' => $this->prospect->status, // eroare?
            // 'subject' => $this->ticket->subject
        ];
    }
}
