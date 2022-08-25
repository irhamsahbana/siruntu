const express = require("express");
const app = express();
const http = require("http");
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = require("socket.io")(server, {
    cors: { origin: "*" },
});
const kurento = require("kurento-client");
const minimist = require("minimist");

let argv = minimist(process.argv.slice(2), {
    default: {
        as_uri: "https://irhams.xyz:3000/",
        ws_uri: "https://irhams.xyz:8888/kurento",
    },
});

/*
 * Definition of global variables.
 */
let candidatesQueue = {};
let kurentoClient = null;
const rooms = [];
let noPresenterMessage = "No active presenter. Try again later...";

io.on("connection", (socket) => {
    console.log("a user connected to signaling server");

    socket.on("error", function () {
        console.log(`Connection ${socket.id} error`);
        stop(socket.id);
    });

    socket.on("disconnect", (socket) => {
        console.log(`user disconnected / connection ${socket.id} closed`);
    });

    socket.on("message", (message) => {
        console.log(`Connection ${socket.id} received message: `,"\x1b[36m", message);

        switch (message.id) {
            case "subscribeToStream":
                subscribeToStream(socket, message.data);
                break;
            case "presenter":
                console.info("case presenter");
                startPresenter(socket, message.sdpOffer, function(error, sdpAnswer) {
                    if (error) {
                        socket.emit("message", {
                            id: "presenterResponse",
                            response: "rejected",
                            message: error,
                        });
                    } else {
                        socket.emit("message", {
                            id: "presenterResponse",
                            response: "accepted",
                            message: sdpAnswer,
                        });

                        console.log(
                            socket.username +
                                " starting publishing to " +
                                socket.room +
                                " room"
                        );
                        socket.broadcast.emit("message", {
                            id: "streamStarted",
                        });
                    }
                });
                break;

            case "viewer":
                console.info("case viewer");
                startViewer(
                    socket,
                    message.sdpOffer,
                    function (error, sdpAnswer) {
                        if (error) {
                            socket.broadcast.emit("message", {
                                id: "viewerResponse",
                                response: "rejected",
                                message: error,
                            });
                        } else {
                            socket.broadcast.emit("message", {
                                id: "viewerResponse",
                                response: "accepted",
                                message: sdpAnswer,
                            });
                        }
                    }
                );
                break;

            case "onIceCandidate":
                console.info("case onIceCandidate");
                onIceCandidate(socket, message.candidate);
                break;
            default:
                console.log("no id message that matched :(");
        }
    });

    // socket.on('subscribeToStream', data => {
    //     let message = {
    //         room: data.room,
    //         username: socket.id,
    //         role: data.role
    //     };

    //     joinRoom(socket, message);
    //     let room = getRoom(socket);
    //     if (room.presenter) socket.emit('streamStarted');
    // });
});

server.listen(3000, () => {
    console.log("listening on *:3000");
});

/*
 * Definition of functions
 */

// Recover kurentoClient for the first time.

function startPresenter(socket, sdpOffer, callback) {
    clearCandidatesQueue(socket);
    let room = getRoom(socket);

    if (room.presenter !== null) {
        stop(socket);
        return callback(
            "Another user is currently acting as presenter. Try again later ..."
        );
    }

    room.presenter = {
        id: socket.id,
        pipeline: null,
        webRtcEndpoint: null,
    };

    getKurentoClient(function (error, kurentoClient) {
        if (error) {
            stop(socket);
            return callback(error);
        }

        if (room.presenter === null) {
            stop(socket);
            return callback(noPresenterMessage);
        }


        kurentoClient.create("MediaPipeline", function (error, pipeline) {
            if (error) {
                stop(socket);
                return callback(error);
            }

            if (room.presenter === null) {
                stop(socket);
                return callback(noPresenterMessage);
            }

            room.presenter.pipeline = pipeline;
            pipeline.create("WebRtcEndpoint", function (error, webRtcEndpoint) {
                if (error) {
                    stop(socket);
                    return callback(error);
                }

                if (room.presenter === null) {
                    stop(socket);
                    return callback(noPresenterMessage);
                }

                room.presenter.webRtcEndpoint = webRtcEndpoint;

                if (candidatesQueue[socket.id]) {
                    while (candidatesQueue[socket.id].length) {
                        let candidate = candidatesQueue[socket.id].shift();
                        webRtcEndpoint.addIceCandidate(candidate);
                    }
                }

                webRtcEndpoint.on("OnIceCandidate", function (event) {
                    let candidate = kurento.getComplexType("IceCandidate")(
                        event.candidate
                    );
                    socket.emit({
                        id: "iceCandidate",
                        candidate: candidate,
                    });
                });

                webRtcEndpoint.processOffer(
                    sdpOffer,
                    function (error, sdpAnswer) {
                        if (error) {
                            stop(socket);
                            return callback(error);
                        }

                        if (room.presenter === null) {
                            stop(socket);
                            return callback(noPresenterMessage);
                        }

                        callback(null, sdpAnswer);
                    }
                );

                webRtcEndpoint.gatherCandidates(function (error) {
                    if (error) {
                        stop(socket);
                        return callback(error);
                    }
                });
            });
        });
    });
}

function startViewer(socket, sdpOffer, callback) {
    clearCandidatesQueue(socket);
    let room = getRoom(socket);

    if (room.presenter === null) {
        stop(socket);
        return callback(noPresenterMessage);
    }

    room.presenter.pipeline.create(
        "WebRtcEndpoint",
        function (error, webRtcEndpoint) {
            if (error) {
                stop(socket);
                return callback(error);
            }
            viewers[socket.id] = {
                webRtcEndpoint: webRtcEndpoint,
                socket: socket,
            };

            if (room.presenter === null) {
                stop(socket);
                return callback(noPresenterMessage);
            }

            if (candidatesQueue[socket.id]) {
                while (candidatesQueue[socket.id].length) {
                    let candidate = candidatesQueue[socket.id].shift();
                    webRtcEndpoint.addIceCandidate(candidate);
                }
            }

            webRtcEndpoint.on("IceCandidateFound", function (event) {
                let candidate = kurento.getComplexType("IceCandidate")(
                    event.candidate
                );

                socket.emit("message", {
                    id: "iceCandidate",
                    candidate: candidate,
                });
            });

            webRtcEndpoint.processOffer(sdpOffer, function (error, sdpAnswer) {
                if (error) {
                    stop(socket);
                    return callback(error);
                }
                if (room.presenter === null) {
                    stop(socket);
                    return callback(noPresenterMessage);
                }

                room.presenter.webRtcEndpoint.connect(
                    webRtcEndpoint,
                    function (error) {
                        if (error) {
                            stop(socket);
                            return callback(error);
                        }
                        if (room.presenter === null) {
                            stop(socket);
                            return callback(noPresenterMessage);
                        }

                        callback(null, sdpAnswer);
                        webRtcEndpoint.gatherCandidates(function (error) {
                            if (error) {
                                stop(socket);
                                return callback(error);
                            }
                        });
                    }
                );
            });
        }
    );
}

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

function stop(socket) {
    let room = getRoom(socket);

    if (room.presenter !== null && room.presenter.id == socket.id)
        stopPresenter(socket);
    else if (room.viewers[socket.id]) stopViewers(socket);
}

const stopPresenter = (socket) => {
    let room = getRoom(socket);
    let viewers = room.viewers;

    for (let i in viewers) {
        let viewer = viewers[i];

        if (viewer.socket) {
            clearCandidatesQueue(socket);
            viewer.webRtcEndpoint.release();

            viewer.socket.emit("message", { id: "stopCommunication" });
        }
    }

    room.presenter.webRtcEndpoint.release();
    room.presenter = null;
    room.pipeline.release();
    room.viewers = [];
};

const stopViewers = (socket) => {
    let room = getRoom(socket);

    clearCandidatesQueue(socket);
    room.viewers[socket.id].webRtcEndpoint.release();

    delete room.viewers[socket.id];
};

function onIceCandidate(socket, _candidate) {
    let candidate = kurento.getComplexType("IceCandidate")(_candidate);
    let room = getRoom(socket);

    if (
        room.presenter &&
        room.presenter.id == socket.id &&
        room.presenter.webRtcEndpoint
    ) {
        console.info("Sending presenter candidate");
        room.presenter.webRtcEndpoint.addIceCandidate(candidate);
    } else if (
        room.viewers[socket.id] &&
        room.viewers[socket.id].webRtcEndpoint
    ) {
        console.info("Sending viewer candidate");
        room.viewer[socket.id].webRtcEndpoint.addIceCandidate(candidate);
    } else {
        console.info("Queueing candidate");
        if (!candidatesQueue[socket.id]) candidatesQueue[socket.id] = [];

        candidatesQueue[socket.id].push(candidate);
    }
}

function clearCandidatesQueue(socket) {
    console.log('clearCandidatesQueue function', candidatesQueue);
    if (candidatesQueue[socket.id]) delete candidatesQueue[socket.id];
}

/**
 *
 * Additional function
 */

const subscribeToStream = (socket, data) => {
    let message = {
        room: data.room,
        username: socket.id,
        role: data.role,
    };

    joinRoom(socket, message);
    let room = getRoom(socket);
    if (room.presenter) socket.emit("message", { id: "streamStarted" });
};

const getRoom = (socket) => {
    console.log('check room dlu yah mas', rooms[socket.room]);
    if (rooms[socket.room] == undefined) createRoom(socket.room);

    console.log('ini room nya  mas', rooms[socket.room]);
    console.log(rooms, 'room yang tersedia sekarang')
    return rooms[socket.room];
};

const createRoom = (room) => {
    rooms[room] = {
        presenter: null,
        pipeline: null,
        viewers: [],
        chat: [],
    };

    console.log('room sekarang', rooms)
};

const joinRoom = (socket, data) => {
    console.log(socket.rooms.length, 'panjangnya')
    while (socket.rooms.length) {
        socket.leave(socket.rooms[0]);
    }

    socket.join(data.room);

    socket.room = data.room;
    socket.username = data.username;
    socket.role = data.role;

    console.log(
        `Joined on room: ${socket.room} \nwith id: ${socket.username} \nwith role: ${socket.role}`
    );
};
