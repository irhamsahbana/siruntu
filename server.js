const express = require("express");
const app = express();
const http = require("http");
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = require("socket.io")(server, {
    cors: { origin: "*" },
});
const kurento = require("kurento-client");
const minimist = require('minimist');

let argv = minimist(process.argv.slice(2), {
    default: {
        as_uri: 'https://irhams.xyz:3000/',
        ws_uri: 'ws://localhost:8888/kurento'
    }
});

/*
 * Definition of global variables.
 */
let candidatesQueue = {};
let kurentoClient = null;
const rooms = [];


io.on("connection", (socket) => {
    console.log("a user connected to signaling server");
    console.log(`Connection received with sessionId ${socket.id}`);

    socket.on("error", function () {
        console.log(`Connection ${socket.id} closed`);
        stop(socket.id);
    });

    socket.on("message", (_message) => {
        let message = JSON.parse(_message);
        console.log(`Connection ${socket.id} received message: ${message}`);

        switch (message.id) {
            case "presenter":
                startPresenter(
                    socket.id,
                    message.sdpOffer,
                    function (error, sdpAnswer) {
                        if (error) {
                            socket.broadcast.emit("presenter-response", {
                                id: "presenterResponse",
                                response: "rejected",
                                message: error,
                            });
                        } else {
                            socket.broadcast.emit("presenter-response", {
                                id: "presenterResponse",
                                response: "rejected",
                                message: sdpAnswer,
                            });
                        }
                    }
                );
                break;

            case "viewer":
                startViewer(socket.id);
        }
    });

    socket.on("disconnect", (socket) => {
        console.log("user disconnected");
    });
});

server.listen(3000, () => {
    console.log("listening on *:3000");
});

/*
 * Definition of functions
 */

// Recover kurentoClient for the first time.
function getKurentoClient(callback) {
    if (kurentoClient !== null) {
        return callback(null, kurentoClient);
    }

    kurento(argv.ws_uri, function (error, _kurentoClient) {
        if (error) {
            console.log(
                "Could not find media server at address " + argv.ws_uri
            );
            return callback(
                "Could not find media server at address" +
                    argv.ws_uri +
                    ". Exiting with error " +
                    error
            );
        }

        kurentoClient = _kurentoClient;
        callback(null, kurentoClient);
    });
}

function start(sessionId, ws, sdpOffer, callback) {
    if (!sessionId) {
        return callback("Cannot use undefined sessionId");
    }

    getKurentoClient(function (error, kurentoClient) {
        if (error) {
            return callback(error);
        }

        kurentoClient.create("MediaPipeline", function (error, pipeline) {
            if (error) {
                return callback(error);
            }

            createMediaElements(pipeline, ws, function (error, webRtcEndpoint) {
                if (error) {
                    pipeline.release();
                    return callback(error);
                }

                if (candidatesQueue[sessionId]) {
                    while (candidatesQueue[sessionId].length) {
                        var candidate = candidatesQueue[sessionId].shift();
                        webRtcEndpoint.addIceCandidate(candidate);
                    }
                }

                connectMediaElements(webRtcEndpoint, function (error) {
                    if (error) {
                        pipeline.release();
                        return callback(error);
                    }

                    webRtcEndpoint.on("IceCandidateFound", function (event) {
                        var candidate = kurento.getComplexType("IceCandidate")(
                            event.candidate
                        );
                        ws.send(
                            JSON.stringify({
                                id: "iceCandidate",
                                candidate: candidate,
                            })
                        );
                    });

                    webRtcEndpoint.processOffer(
                        sdpOffer,
                        function (error, sdpAnswer) {
                            if (error) {
                                pipeline.release();
                                return callback(error);
                            }

                            sessions[sessionId] = {
                                pipeline: pipeline,
                                webRtcEndpoint: webRtcEndpoint,
                            };
                            return callback(null, sdpAnswer);
                        }
                    );

                    webRtcEndpoint.gatherCandidates(function (error) {
                        if (error) {
                            return callback(error);
                        }
                    });
                });
            });
        });
    });
}

function createMediaElements(pipeline, ws, callback) {
    pipeline.create("WebRtcEndpoint", function (error, webRtcEndpoint) {
        if (error) {
            return callback(error);
        }

        return callback(null, webRtcEndpoint);
    });
}

function connectMediaElements(webRtcEndpoint, callback) {
    webRtcEndpoint.connect(webRtcEndpoint, function (error) {
        if (error) {
            return callback(error);
        }
        return callback(null);
    });
}

function stop(sessionId) {
    if (sessions[sessionId]) {
        var pipeline = sessions[sessionId].pipeline;
        console.info("Releasing pipeline");
        pipeline.release();

        delete sessions[sessionId];
        delete candidatesQueue[sessionId];
    }
}

function onIceCandidate(sessionId, _candidate) {
    var candidate = kurento.getComplexType("IceCandidate")(_candidate);

    if (sessions[sessionId]) {
        console.info("Sending candidate");
        var webRtcEndpoint = sessions[sessionId].webRtcEndpoint;
        webRtcEndpoint.addIceCandidate(candidate);
    } else {
        console.info("Queueing candidate");
        if (!candidatesQueue[sessionId]) {
            candidatesQueue[sessionId] = [];
        }
        candidatesQueue[sessionId].push(candidate);
    }
}


/**
 * 
 * Additional function 
 */

const getRoom = (socket) => {
    if (rooms[socket.room] == undefined) {
        createRoom(socket.room);
    }

    return rooms[socket.room];
};

const createRoom = (room) => {
    rooms[room] = {
        presenter: null,
        pipeline: null,
        viewers: [],
        chat: [],
    };
};

const joinRoom = (socket, data) => {
    while (socket.rooms.length) {
        socket.leave(socket.rooms[0]);
    }

    socket.join(data.room);
    socket.room = data.room;
    socket.username = data.username;
    socket.role = data.role;

    console.log(
        `Joined on room: ${data.room} with id : ${data.username} with role : ${data.role}`
    );
};

//Stop Presenter
const stopPresenter = (socket) => {
    var room = getRoom(socket);
    var viewers = room.viewers;
  
    for (var i in viewers) {
    var viewer = viewers[i];
      if (viewer.socket) {
        clearCandidatesQueue(socket);
        viewer.webRtcEndpoint.release();
        viewer.socket.emit('stopCommunication');
      }
    }
  
    room.presenter.webRtcEndpoint.release();
    room.presenter = null;
    room.pipeline.release();
    room.viewers = [];
  };
  
  //Method Stop Viewer
  stopViewing = (socket) => {
    var room = getRoom(socket);
    clearCandidatesQueue(socket.id);
    room.viewers[socket.id].webRtcEndpoint.release();
    delete room.viewers[socket.id];
  };

const acceptPeerResponse = (peerType, sdpAnswer) => {
    return {
        id: peerType + "Response",
        response: "accepted",
        sdpAnswer: sdpAnswer,
    };
};

const rejectPeerResponse = (peerType, reason) => {
    return {
        id: peerType + "Response",
        response: "rejected",
        message: reason,
    };
};