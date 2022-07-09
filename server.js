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
let noPresenterMessage = 'No active presenter. Try again later...';

io.on("connection", socket => {
    console.log("a user connected to signaling server");
    console.log(`Connection received with sessionId ${socket.id}`);

    socket.on("error", function () {
        console.log(`Connection ${socket.id} error`);
        stop(socket.id);
    });

    socket.on("disconnect", socket => {
        console.log(`user disconnected / connection ${socket.id} closed`);
    });

    socket.on("message", (message) => {
        console.log(`Connection ${socket.id} received message: ${message}`);

        switch (message.id) {
            case "presenter":
                startPresenter(
                    socket,
                    message.sdpOffer,
                    (error, sdpAnswer) => {
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

                            console.log(socket.username + ' starting publishing to ' + socket.room + ' room');
                            socket.broadcast.emit("message", {
                                id: 'streamStarted'
                            });
                        }
                    }
                );
                break;

            case "viewer":
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
        }
    });

    socket.on('subscribeToStream', data => {
        let message = {
            room: data.room,
            username: socket.id,
            role: data.role
        };

        joinRoom(socket, message);
        let room = getRoom(socket);
        if (room.presenter) socket.emit('streamStarted');
    });
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
		stop(socket.id);
		return callback("Another user is currently acting as presenter. Try again later ...");
	}

	presenter = {
		id : socket.id,
		pipeline : null,
		webRtcEndpoint : null
	}

	getKurentoClient(function(error, kurentoClient) {
		if (error) {
			stop(socket.id);
			return callback(error);
		}

		if (presenter === null) {
			stop(socket.id);
			return callback(noPresenterMessage);
		}

		kurentoClient.create('MediaPipeline', function(error, pipeline) {
			if (error) {
				stop(socket.id);
				return callback(error);
			}

			if (presenter === null) {
				stop(socket.id);
				return callback(noPresenterMessage);
			}

			presenter.pipeline = pipeline;
			pipeline.create('WebRtcEndpoint', function(error, webRtcEndpoint) {
				if (error) {
					stop(socket.id);
					return callback(error);
				}

				if (presenter === null) {
					stop(socket.id);
					return callback(noPresenterMessage);
				}

				presenter.webRtcEndpoint = webRtcEndpoint;

                if (candidatesQueue[socket.id]) {
                    while(candidatesQueue[socket.id].length) {
                        var candidate = candidatesQueue[socket.id].shift();
                        webRtcEndpoint.addIceCandidate(candidate);
                    }
                }

                webRtcEndpoint.on('IceCandidateFound', function(event) {
                    var candidate = kurento.getComplexType('IceCandidate')(event.candidate);
                    socket.emit({
                        id : 'iceCandidate',
                        candidate : candidate
                    });
                });

				webRtcEndpoint.processOffer(sdpOffer, function(error, sdpAnswer) {
					if (error) {
						stop(socket.id);
						return callback(error);
					}

					if (presenter === null) {
						stop(socket.id);
						return callback(noPresenterMessage);
					}

					callback(null, sdpAnswer);
				});

                webRtcEndpoint.gatherCandidates(function(error) {
                    if (error) {
                        stop(socket.id);
                        return callback(error);
                    }
                });
            });
        });
	});
}

function startViewer(socket, sdpOffer, callback) {
	clearCandidatesQueue(socket);

	if (presenter === null) {
		stop(sessionId);
		return callback(noPresenterMessage);
	}

	presenter.pipeline.create('WebRtcEndpoint', function(error, webRtcEndpoint) {
		if (error) {
			stop(sessionId);
			return callback(error);
		}
		viewers[sessionId] = {
			"webRtcEndpoint" : webRtcEndpoint,
			"ws" : ws
		}

		if (presenter === null) {
			stop(sessionId);
			return callback(noPresenterMessage);
		}

		if (candidatesQueue[sessionId]) {
			while(candidatesQueue[sessionId].length) {
				var candidate = candidatesQueue[sessionId].shift();
				webRtcEndpoint.addIceCandidate(candidate);
			}
		}

        webRtcEndpoint.on('IceCandidateFound', function(event) {
            var candidate = kurento.getComplexType('IceCandidate')(event.candidate);
            ws.send(JSON.stringify({
                id : 'iceCandidate',
                candidate : candidate
            }));
        });

		webRtcEndpoint.processOffer(sdpOffer, function(error, sdpAnswer) {
			if (error) {
				stop(sessionId);
				return callback(error);
			}
			if (presenter === null) {
				stop(sessionId);
				return callback(noPresenterMessage);
			}

			presenter.webRtcEndpoint.connect(webRtcEndpoint, function(error) {
				if (error) {
					stop(sessionId);
					return callback(error);
				}
				if (presenter === null) {
					stop(sessionId);
					return callback(noPresenterMessage);
				}

				callback(null, sdpAnswer);
		        webRtcEndpoint.gatherCandidates(function(error) {
		            if (error) {
			            stop(sessionId);
			            return callback(error);
		            }
		        });
		    });
	    });
	});
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

// function stop from kurento documentation
// function stop(socket) {
//     if (sessions[sessionId]) {
//         var pipeline = sessions[sessionId].pipeline;
//         console.info("Releasing pipeline");
//         pipeline.release();

//         delete sessions[sessionId];
//         delete candidatesQueue[sessionId];
//     }
// }

const stop = socket => {
    let room = getRoom(socket);

    if (room.presenter !== null && room.presenter.id == socket.id) stopPresenter(socket);
    else if (room.viewers[socket.id]) stopViewers(socket);
};

const stopPresenter = socket => {
    let room = getRoom(socket);
    let viewers = room.viewers;

    for (let i in viewers) {
        let viewer = viewers[i];

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

const stopViewers = socket => {
    let room = getRoom(socket);

    clearCandidatesQueue(socket);
    room.viewers[socket.id].webRtcEndpoint.release();

    delete room.viewers[socket.id];
};

function onIceCandidate(socket, _candidate) {
    var candidate = kurento.getComplexType("IceCandidate")(_candidate);

    if (sessions[socket]) {
        console.info("Sending candidate");
        var webRtcEndpoint = sessions[socket].webRtcEndpoint;
        webRtcEndpoint.addIceCandidate(candidate);
    } else {
        console.info("Queueing candidate");
        if (!candidatesQueue[socket]) {
            candidatesQueue[socket] = [];
        }
        candidatesQueue[socket].push(candidate);
    }
}

// function clearCandidatesQueue from kurento documentation
// function clearCandidatesQueue(sessionId) {
// 	if (candidatesQueue[sessionId]) {
// 		delete candidatesQueue[sessionId];
// 	}
// }

const clearCandidatesQueue = socket => {
    if (candidatesQueue[socket.id]) delete candidatesQueue[socket.id];
};


/**
 * 
 * Additional function 
 */

const getRoom = (socket) => {
    if (rooms[socket.room] == undefined)
        createRoom(socket.room);

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

    // socket.room = data.room;
    socket.username = data.username;
    socket.role = data.role;

    console.log(`Joined on room: ${data.room} with id : ${data.username} with role : ${data.role}`);
};