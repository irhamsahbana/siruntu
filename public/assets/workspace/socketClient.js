// const hostname = "144.126.243.118";
// const port = "3000";

// const socket = io(`${hostname}:${port}`);
const socket = io('irhams.xyz');


socket.on('connect', socket => {
    console.log('do something when our client can connect to our signaling server');
});