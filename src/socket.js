//var app = express();
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var port = process.env.PORT || 3000;
var redis = new Redis();
var client = require("redis").createClient();

redis.subscribe('subscribe-to-channel', function (err, count) {
    console.log("subscribed to subscribe-to-channel")
});

redis.on('message', function (channel, message) {

    message = JSON.parse(message);

    if (message.event === "Ivfuture\\EventNotification\\Events\\SubscribeToChannel") {

        client.pubsub('CHANNELS', function (err, result) {

            if (!err && !result.includes(message.data.channel)) {
                redis.subscribe(message.data.channel, function (err, count) {
                    console.log("subscribed to " + message.data.channel)
                });
            }
        });

    } else {
        console.log("Message: " + JSON.stringify(message.data));
        io.emit(channel + ':' + message.event, message.data);
    }

});

http.listen(port, function(){
    console.log('Listening on Port '+ port);
});

