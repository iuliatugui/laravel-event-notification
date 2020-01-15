<?php

namespace Ivfuture\EventNotification\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscribeToChannel implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channel;

    /**
     * Send as message the channel that Redis should subscribe to
     *
     * @return void
     */
    public function __construct($channel)
    {
        $this->channel = $channel;

    }

    /**
     * Get the channel the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['subscribe-to-channel'];
    }
}

