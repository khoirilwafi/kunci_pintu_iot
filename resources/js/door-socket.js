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

const channel = echo.private(`private.dashboard.${user.id}`);

echo.connector.pusher.connection.bind('connected', () => {
    Livewire.emit('socketEvent', 'Terhubung');
});

channel.listen(".door-event", (event) => {
    Livewire.emit('doorEvent', event);
});
