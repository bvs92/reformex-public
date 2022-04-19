<?php

namespace App\Events;

use App\Quote;
use App\Timeline;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QuoteFileDeletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $quote, $timeline;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Timeline $timeline, Quote $quote)
    {
        $this->quote = $quote;
        $this->timeline = $timeline;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('pro-quotes-channel.' . $this->quote->id);
        return new PrivateChannel('pro-timelines-channel.' . $this->timeline->id);
    }


    public function broadcastWith()
    {
        return [
            'quote_id' => $this->quote->id,
            'timeline_id' => $this->timeline->id
            // 'user' => $this->user->getTheName(), // eroare?
            // 'subject' => $this->ticket->subject
        ];
    }
}
