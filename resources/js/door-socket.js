import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: "pusher",
    key: "aNmB0bkbrE1PS6K07nrt",
    cluster: "mt1",
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    encrypted: false,
    enabledTransports: ["ws", "wss"],
});

echo.connector.pusher.connection.bind('error', () => {
    Livewire.emit('socketEvent', { text: "Terputus", color: "red" });
});

echo.connector.pusher.connection.bind('closed', () => {
    Livewire.emit('socketEvent', { text: "Terputus", color: "red" });
});

const channel = echo.join(`office.${office}`);

channel.here(() => {
    Livewire.emit('socketEvent', { text: "Terhubung", color: "white" });
});

channel.leaving((door) => {
    console.log('device keluar', door);
});

channel.listen('.door-status', () => {
    Livewire.emit('doorStatusEvent');
});
