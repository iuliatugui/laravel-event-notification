# Laravel Event Notification

This package provides an easy way to integrate notifications with Laravel 6, Redis and socket.io .
After the installation the user will be able to receive real time notification.

Here are some examples:

```php
// at the top of your class
use NotificationTrait;

//Controller's constructor
public function __construct()
{
    //subscribe to all the notification channels from database
    $this->subscribeToNotificationChannels();

    // ...

}

//sending a notification

$notification_channel = NotificationChannel::where('label', 'post-liked')->first();
if (!isset($notification_channel)) {
    return response()->json(['success' => false]);
}

$this->sendNotification($p_sender_id, $p_receiver_id, $p_notifiable_type, $p_notifiable_id, $notification_channel->id, $p_title, $p_description);

```

## Installation

You can install this package via composer using:

```bash
composer require ivfuture/laravel-event-notification
```

To register the package you have to add the service provider in your ```config/app.php``` file:

```php
'providers' => [
    // ...
    Ivfuture\EventNotification\NotificationServiceProvider::class,
];
```
Now you should publish the migration with:

```bash
php artisan vendor:publish --provider="Ivfuture\EventNotification\NotificationServiceProvider"
```

After the migration have been published you can create the tables by running the migrations:

```bash
php artisan migrate
```

Next, we have to install the npm packages:

```bash
npm install express socket.io ioredis redis  --save
```

## Settings
First of all, you have to edit your ```.env``` file to tell Laravel to use the correct ```BROADCAST_DRIVER```.

```
BROADCAST_DRIVER=redis
```

If you are using an older version of Laravel you may skip the next step.
You have to edit your ```config/database.php``` file and comment the line that is adding a prefix to redis:

```php
'redis' => [

    'options' => [
        'cluster' => env('REDIS_CLUSTER', 'redis'),
        //'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
    ],

   // ...
],
```

## Starting the servers

Now all you have to do is start the servers.

```bash
node  vendor/ivfuture/laravel-event-notification/src/socket.js
redis-server --port 3001
```

## Usage
After you've installed the package and done all the settings it's time to integrate the notifications in your project.

First, don't forget to import the trait at the top of your file.
```php
use NotificationTrait;
```

### Subscribe to channels notification

In your Controller constructor you must subscribe to the channels. You can do this by using ```NotificationTrait```'s function ```subscribeToNotificationChannels()```. It will search in ```notification_type``` table for channels and will automatically subscribe to them.
```php
public function __construct()
{
    $this->subscribeToNotificationChannels();

    // ...

}
```

### Send a notification

There is also a convenient function for sending a notification:
```php

$notification_channel = NotificationChannel::where('label', 'post-liked')->first();

$this->sendNotification($p_sender_id, $p_receiver_id, $p_notifiable_type, $p_notifiable_id, $notification_channel->id, $p_title, $p_description);

```
This will save the notification into the database and will send an event with all its data.

### Get notifications from database
You can get the notifications from database:
```php
Notification::latest()->where('receiver_id', auth()->user()->id )->get()->groupByDate();
```
This will provide us with notifications grouped by time categories like: ```today```, ```this week```, ```last week``` or ```older```.

### Receive the notification in view

To receive real time notifications you must follow this 3 steps:

1 - Import socket.io library
```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.dev.js"></script>
```
2 - Create a Socket object. 
```javascript
var socket = io('{{ env("APP_URL") }}:3000');
```
Note: By default the server wil listen for port 3000. You can change that by editing the value of ```PORT```  in your ```.env``` file.

3 - Listen to the channel from which notification was sent.
```javascript
socket.on("post-liked:App\\Events\\SendNotification", function (data) {
    // ...
});
```
Note: If you have many channels, you must listen to all of them.

Example:
```javascript
socket.on("post-liked:App\\Events\\SendNotification", function (data) {
    // ...
});

socket.on("post-commented:App\\Events\\SendNotification", function (data) {
    // ...
});
```
And finally, don't forget to check if the user is the receiver of notification.
```javascript
if (data.receiver_id == "{{auth()->user()->id}}") {
    // ...   
}
```
