<?php

namespace Ivfuture\EventNotification\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Ivfuture\EventNotification\Models\NotificationChannel;

class SendNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $sender_id;
    public $receiver_id;
    public $notifiable_type;
    public $notifiable_id;
    public $title;
    public $channel;
    public $description;

    /**
     * Send as message the notification data
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->id = $notification->id;
        $this->sender_id = $notification->sender_id;
        $this->receiver_id  = $notification->receiver_id;
        $this->notifiable_type = $notification->notifiable_type;
        $this->notifiable_id = $notification->notifiable_id;
        $this->title = $notification->title;
        $this->channel = $notification->channel;
        $this->description = $notification->description;
    }

    /**
     * Get the channel the notification should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $channel = NotificationChannel::findOrFail($this->channel)->first();
        return  [$channel->label];
    }
}
