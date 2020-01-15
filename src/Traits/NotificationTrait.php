<?php

namespace Ivfuture\EventNotification\Traits;

use Ivfuture\EventNotification\Events\SendNotification;
use Ivfuture\EventNotification\Events\SubscribeToChannel;
use Ivfuture\EventNotification\Models\Notification;
use Ivfuture\EventNotification\Models\NotificationChannel;

trait NotificationTrait
{
    /**
     * Sending to Redis the channels he should subscribe
     */
    public function subscribeToNotificationChannels()
    {
        // Searching for all the notification channels from database
        $notification_channels = NotificationChannel::all();

        foreach ($notification_channels as $channel) {
            // Creating an SubscribeToChannel event for every channel
            event(new SubscribeToChannel($channel->label));
        }
    }

    /**
     * Sending an notification to Redis
     *
     * @param $sender_id        =>    the ID of the user that is triggering the notification
     * @param $receiver_id      =>    the ID of the user that is receiving the notification
     * @param $notifiable_type  =>    the class name of the parent model
     * @param $notifiable_id    =>    the ID value of the object which stays at the base of notification
     * @param $channel_id       =>    the ID is a foreign key to notification_channel table
     * @param $title            =>    the notification title
     * @param $description      =>    the notification description
     */
    public function sendNotification($sender_id, $receiver_id, $notifiable_type, $notifiable_id, $channel_id, $title, $description)
    {
        // Create a new Notification object and save it into the database
        $notification = new Notification();
        $notification->saveNotification($sender_id, $receiver_id, $notifiable_type, $notifiable_id, $channel_id, $title, $description);

        // Create an SendNotification event with the notification data
        event(new SendNotification($notification));

    }
}
