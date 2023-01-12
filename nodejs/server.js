var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server, {
    cors: {
        origin: "http://127.0.0.1:8000",
        methods: ["GET", "POST"]
    }
});
var redis = require('redis');
// const cors = require('cors');

// app.use(cors());
server.listen(8890, function () {
    console.log('server started!')
});
// app.options('*', cors())
users = {};

const redisClient = redis.createClient();

//if you set a password for your redis server
/*
redisClient.auth('password', function(err){
	if (err) throw err;
});
*/

// redisClient.subscribe('message');
//
// redisClient.on("message", function(channel, data) {
//     console.log('dataed');
//     var data = JSON.parse(data);
//     if(data.client_id in users){
//         if(data.conversation_id in users[data.client_id]){
//             users[data.client_id][data.conversation_id].emit("message", {"conversation_id":data.conversation_id,"msg":data.text});
//         }
//     }
// });

(async () => {

    const client = redis.createClient();

    const subscriber = client.duplicate();

    await subscriber.connect();

    await subscriber.subscribe('message', (message) => {
        console.log(message); // 'message'
        const data = JSON.parse(message);
        if(data.client_id in users){
            if(data.conversation_id in users[data.client_id]){
                users[data.client_id][data.conversation_id].emit("message", {"conversation_id":data.conversation_id,"msg":data.text});
            }
        }
    });

})();

io.on('connection', function (socket) {

    socket.on("add user",function(data){
        if(!(data.client in users)){
            users[data.client] = {};
        }
        users[data.client][data.conversation]=socket;
        socket.user_id = data.client;
        socket.conversation_id = data.conversation;
        console.log('user connected');
    });

    socket.on('disconnect', function() {
        if(!(socket.user_id in users)) return;
        if(!(socket.conversation_id in users[socket.user_id])) return;

        delete users[socket.user_id][socket.conversation_id];
        if(Object.keys(users[socket.user_id]).length === 0){
            delete users[socket.user_id];
        }
        console.log('user diconnetctted');
    });

});
