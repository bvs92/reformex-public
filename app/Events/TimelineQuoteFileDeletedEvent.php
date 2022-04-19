<?php

namespace App\Events;

use App\Timeline;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TimelineQuoteFileDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timeline, $file_name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timeline $timeline, $file_name)
    {
        $this->timeline = $timeline;
        $this->file_name = $file_name;
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
            'file_name'     => $this->file_name
            // 'user' => $this->user->getTheName(), // eroare?
            // 'subject' => $this->ticket->subject
        ];
    }
}
