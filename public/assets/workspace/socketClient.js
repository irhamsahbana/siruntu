const hostname = "127.0.0.1";
const port = "3000";

const socket = io(`${hostname}:${port}`);
socket.on("connection");