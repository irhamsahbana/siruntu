// const hostname = "144.126.243.118";
// const port = "3000";

// const socket = io(`${hostname}:${port}`);

// in case signaling server is same with your application domain
const socket = io(location.host);

let webRtcPeer;
let video = document.getElementById('video');
let autoView = true;
let role = 'presenter';
let role2 = 'viewer';
const room = $('#room').val();

$(function() {
    document.getElementById('broadcastWebcam').addEventListener('click', function() { presenter(); } );
    document.getElementById('broadcastScreenshare').addEventListener('click', function() { presenter('screen'); } );
	document.getElementById('terminate').addEventListener('click', function() { stop(); } );
});

socket.on('connect', socket => {
    console.log('do something when our client can connect to our signaling server');

    let data = {
        room: getRoom(),
        role: role
    };

    socket.emit('subscribeToStream', data);
});

socket.on('message', function(message) {
    console.info('received message: ' + message.data);

    switch (message.id) {
        case 'PresenterResponse':
            presenterResponse(message);
            break;
        case 'viewerResponse':
            viewerResponse(message);
            break;
        case 'stopCommunication':
            dispose();
            break;
        case 'iceCandidate':
            webRtcPeer.addIceCandidate(message.candidate);
            break;
        case 'streamStarted':
            if (autoView) viewer();
        default:
            console.error('Unrecognized message: ', message);

    }
});

function presenterResponse(message) {
    if (message.response != 'accepted') {
        let errorMsg = message.message ? message.message : 'Unknow error';

        console.warn('Call not accepted for the following reason: ' + errorMsg);
        dispose();
    } else {
        console.info(`From presenter, webRTCPeer ${webRtcPeer}, sdpAnswer ${message.sdpAnswer}`)
        webRtcPeer.processAnswer(message.sdpAnswer);
    }
}

function viewerResponse(message) {
    if (message.response != 'accepted') {
        let errorMsg = message.message ? message.message : 'Unknow error';

        console.warn('Call not accepted for the following reason: ' + errorMsg);
        dispose();
    } else {
        webRtcPeer.processAnswer(message.sdpAnswer);
    }
}

function presenter(sendSource = 'webcam') {
    if (!webRtcPeer) {
        // showSpinner(video);

        let options = {
            localVideo: video,
            onicecandidate: onIceCandidate,
            sendSource: sendSource
        }

        webRtcPeer = kurentoUtils.WebRtcPeer.WebRtcPeerSendonly(options, function(error) {
            if(error) return onError(error);
            this.generateOffer(onOfferPresenter);
        });
    }
}

function onOfferPresenter(error, offerSdp) {
    if (error) return onError(error);

    let message = {
        id : 'presenter',
        sdpOffer : offerSdp
    };

    sendMessage(message);
}

function viewer() {
    if (!webRtcPeer) {
        // showSpinner(video);

        let options = {
            remoteVideo: video,
            onicecandidate : onIceCandidate
        }

        webRtcPeer = kurentoUtils.WebRtcPeer.WebRtcPeerRecvonly(options, function(error) {
            if(error) return onError(error);

            this.generateOffer(onOfferViewer);
        });
    }
}

function onOfferViewer(error, offerSdp) {
    if (error) return onError(error)

    let message = {
        id : 'viewer',
        sdpOffer : offerSdp
    }

    sendMessage(message);
}

function onIceCandidate(candidate) {
    console.log('Local candidate: ' + candidate);

    let message = {
       id : 'onIceCandidate',
       candidate : candidate
    }

    sendMessage(message);
}

function stop() {
    if (webRtcPeer) {
        let message = {
            id : 'stop'
        }

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
 console.log('Sending message: ' + message);

 socket.emit(message);
}

function getRoom() {
    return room;
}