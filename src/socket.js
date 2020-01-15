var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var port = process.env.PORT || 3000;
var redis = new Redis();
var client = require("redis").createClient();

// Subscribe to SubscribeToChannel event's channel
redis.subscribe('subscribe-to-channel', function (err, count) {
    console.log("subscribed to subscribe-to-channel")
});

redis.on('message', function (channel, message) {

    message = JSON.parse(message);

    // Check if the received message was sent from SubscribeToChannel event
    if (message.event === "Ivfuture\\EventNotification\\Events\\SubscribeToChannel") {

        // Get the channels that we're already subscribed
        client.pubsub('CHANNELS', function (err, result) {

            // Check if we haven't subscribed to the channel which SubscribeToChannel event sent us
            if (!err && !result.includes(message.data.channel)) {

                // Subscribe to the channel
                redis.subscribe(message.data.channel, function (err, count) {
                    console.log("subscribed to " + message.data.channel)
                });
            }
        });

    }
    // If the sent message doesn't belong to SubscribeToChannel event
    // In order words, it belongs to SendNotification
    else {
        // Emit the message data to socket
        io.emit(channel, message.data);
        console.log("Message: " + JSON.stringify(message.data));
    }

});

http.listen(port, function(){
    console.log('Listening on Port '+ port);
});

