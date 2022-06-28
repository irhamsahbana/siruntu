$(document).ready(function () {
    let webRtcPeer;
    let video = $('#video');
    let room = $('#room').val();

    let btnShareVideo = $('btn-share-video');
    let btnShareScreen = $('btn-share-screen');


    btnShareScreen.on('click', function () {
        shareScreen(); 
    });

    btnShareVideo.on('click', function () { 
        shareVideo();
    });
                    
});