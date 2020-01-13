<?php

namespace Ivfuture\EventNotification\Traits;

use Ivfuture\EventNotification\Events\SendNotification;
use Ivfuture\EventNotification\Events\SubscribeToChannel;
use Ivfuture\EventNotification\Models\Notification;
use Ivfuture\EventNotification\Models\NotificationChannel;

trait NotificationTrait
{
    public function subscribeToNotificationChannels()
    {
        $notification_channels = NotificationChannel::all();
        foreach ($notification_channels as $channel) {
            event(new SubscribeToChannel($channel->label));
        }
    }

    public function sendNotification($sender_id, $receiver_id, $notifiable_type, $notifiable_id, $channel_id, $title, $description)
    {
        $notification = new Notification();
        $notification->saveNotification($sender_id, $receiver_id, $notifiable_type, $notifiable_id, $channel_id, $title, $description);

        event(new SendNotification($notification));

    }
}
