const hostname = "144.126.243.118";
const port = "3000";

const socket = io(`${hostname}:${port}`);
socket.on("connection");