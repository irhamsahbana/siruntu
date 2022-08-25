// const hostname = "144.126.243.118";
// const port = "3000";

// const socket = io(`${hostname}:${port}`);

// in case signaling server is same with your application domain
// var getScreenConstraints = (function(error, screen_constraints) {
//     if (error) {
//         return alert(error);
//     }

//     if(screen_constraints.canRequestAudioTrack) {
//         // you can capture speakers
//         // getUserMedia({audio:screen_constraints})
//     }

//     navigator.mediaDevices.getUserMedia({
//         video: screen_constraints
//     }).then(function(stream) {
//         var video = document.querySelector('video');
//         video.src = URL.createObjectURL(stream);
//         video.play();
//     }).catch(function(error) {
//         alert(JSON.stringify(error, null, '\t'));
//     });
// });

const socket = io(location.host);

let webRtcPeer;
let video = document.getElementById("video");
let autoView = true;
let personCategory = document.getElementById('person_category').value;
let role = personCategory == 'lecturer' ? 'presenter' : 'viewer';
const room = $("#room").val();

$(function () {
    document
        .getElementById("broadcastWebcam")
        .addEventListener("click", function () {
            presenter();
        });
    document
        .getElementById("broadcastScreenshare")
        .addEventListener("click", function () {
            presenterScreenshare();
        });
    document.getElementById("terminate").addEventListener("click", function () {
        stop();
    });
    document.getElementById("view").addEventListener("click", function () {
        viewer();
    });
});

socket.on("connect", () => {
    console.log(
        "do something when our client can connect to our signaling server"
    );

    let data = {
        room: getRoom(),
        role: role,
    };

    sendMessage({id: "subscribeToStream", data: data})
    // socket.emit("subscribeToStream", data);
});

socket.on("message", function (message) {
    console.info("received message: ", message);

    switch (message.id) {
        case "PresenterResponse":
            console.info("case PresenterRespose")
            presenterResponse(message);
            break;
        case "viewerResponse":
            console.info("case viewerResponse")
            viewerResponse(message);
            break;
        case "stopCommunication":
            console.info("case stopCommunication")
            dispose();
            break;
        case "iceCandidate":
            console.info("case iceCandidate")
            webRtcPeer.addIceCandidate(message.candidate);
            break;
        case "streamStarted":
            console.info("case streamStarted")
            if (autoView) viewer();
        default:
            console.error("Unrecognized message: ", message);
    }
});

function presenterResponse(message) {
    console.log('presenterResponse function', message);
    if (message.response != "accepted") {
        let errorMsg = message.message ? message.message : "Unknow error";

        console.warn("Call not accepted for the following reason: " + errorMsg);
        dispose();
    } else {
        console.info(
            `From presenter, webRTCPeer ${webRtcPeer}, sdpAnswer ${message.sdpAnswer}`
        );
        webRtcPeer.processAnswer(message.sdpAnswer);
    }
}

function viewerResponse(message) {
    if (message.response != "accepted") {
        let errorMsg = message.message ? message.message : "Unknow error";

        console.warn("Call not accepted for the following reason: " + errorMsg);
        dispose();
    } else {
        webRtcPeer.processAnswer(message.sdpAnswer);
    }
}

function presenter() {
    if (!webRtcPeer) {
        let constraints = {
            audio: true,
            video: {
                width: 640,
                framerate: 15,
            },
        };

        let options = {
            localVideo: video,
            onicecandidate: onIceCandidate,
            mediaConstraints: constraints,
        };

        webRtcPeer = kurentoUtils.WebRtcPeer.WebRtcPeerSendonly(
            options,
            function (error) {
                if (error) return onError(error);
                this.generateOffer(onOfferPresenter);
            }
        );
    }
}

function presenterScreenshare() {
    if (!webRtcPeer) {
        function onGetStream(stream) {
            video.srcObject = stream;
            let options = {
                onicecandidate: onIceCandidate,
                videoStream: stream,
            };

            webRtcPeer = kurentoUtils.WebRtcPeer.WebRtcPeerSendrecv(
                options,
                function (error) {
                    if (error) return onError(error);

                    this.generateOffer(onOfferPresentScreen);
                }
            );
        }

        if (navigator.mediaDevices.getDisplayMedia) {
            navigator.mediaDevices
                .getDisplayMedia({ video: false })
                .then((stream) => {
                    onGetStream(stream);
                }, onError)
                .catch(onError);
        } else if (navigator.getDisplayMedia) {
            navigator
                .getDisplayMedia({ video: false })
                .then((stream) => {
                    onGetStream(stream);
                }, onError)
                .catch(onError);
        }
    }
}

function onOfferPresenter(error, offerSdp) {
    if (error) return onError(error);

    let message = {
        id: "presenter",
        sdpOffer: offerSdp,
    };

    console.log('onOfferPresenter function', message)

    sendMessage(message);
}

function viewer() {
    if (!webRtcPeer) {
        // showSpinner(video);

        let options = {
            remoteVideo: video,
            onicecandidate: onIceCandidate,
        };

        webRtcPeer = kurentoUtils.WebRtcPeer.WebRtcPeerRecvonly(
            options,
            function (error) {
                if (error) return onError(error);

                this.generateOffer(onOfferViewer);
            }
        );
    }
}

function onOfferViewer(error, offerSdp) {
    if (error) return onError(error);

    let message = {
        id: "viewer",
        sdpOffer: offerSdp,
    };

    sendMessage(message);
}

function onIceCandidate(candidate) {
    console.log("Local candidate: ", candidate);

    let message = {
        id: "onIceCandidate",
        candidate: candidate,
    };

    sendMessage(message);
}

function stop() {
    if (webRtcPeer) {
        let message = {
            id: "stop",
        };

        sendMessage(message);
        dispose();
    }
}

function dispose() {
    if (webRtcPeer) {
        webRtcPeer.dispose();
        webRtcPeer = null;
    }

    // hideSpinner(video);
}

function sendMessage(message) {
    console.log("Sending message: ", message);

    socket.emit("message", message);
}

function onError(error) {
    console.log("onError", error);
}

function getRoom() {
    return room;
}
