let domen = 'http://myinvisiont.mm';
const http = require("http");
const express = require( "express");
const axios = require( "axios");
const WebSocket = require( "ws");

const app = express();

const server = http.createServer(app);

const webSocketServer = new WebSocket.Server({ server });

webSocketServer.on('connection', ws => {
    ws.on('message', message => {
        let parsedMessage = JSON.parse(message);
        console.log(parsedMessage.file)
        axios.post(domen + '/chat/send_message_to_thread/',  {
            'body' : parsedMessage.body,
            'thread_id' : parsedMessage.thread_id,
            'user_id' : parsedMessage.user_id,
            'token' : parsedMessage.token,
            'file' : parsedMessage.file,
        }).then(response => {
            webSocketServer.clients.forEach(client => {
                client.send(JSON.stringify(response.data));
                }
            );
            // this.threadMessages = response.data
            // client.send(m)
        }).catch(error => {
            webSocketServer.clients.forEach(client => {
                    // client.send(domen + '/chat/send_message_to_thread/');
                    // client.send(JSON.stringify(parsedMessage));
                    client.send(JSON.stringify(error));
                }
            );
        })

        // io.emit('show users', 'hello you too');
    });
    //
    // ws.on("error", e => ws.send(e));
    //
    // ws.send('Hi there, I am a WebSocket server');
});

// webSocketServer.on('send', (id) => {
//     io.emit('show users', 'hello you too')
// })
// webSocketServer.on('test', (id) => {
//     io.emit('show users', 'hello you too')
// })

server.listen(8999, () => console.log("Server started"))