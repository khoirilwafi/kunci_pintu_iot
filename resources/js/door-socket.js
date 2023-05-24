import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: "pusher",
    key: "aNmB0bkbrE1PS6K07nrt",
    cluster: "mt1",
    wsHost: "localhost",
    wsPort: 6001,
    forceTLS: false,
    encrypted: false,
    enabledTransports: ["ws"],
});

echo.connector.pusher.connection.bind('error', () => {
    Livewire.emit('socketEvent', { text: "Terputus", color: "red" });
    alert('Koneksi websocket terputus, kondisi pintu tidak bisa terpantau secara realtime.');
});

echo.connector.pusher.connection.bind('closed', () => {
    Livewire.emit('socketEvent', { text: "Terputus", color: "red" });
    alert('Koneksi websocket terputus, kondisi pintu tidak bisa terpantau secara realtime.');
});

const channel = echo.join(`office.${office}`);

channel.here(() => {
    Livewire.emit('socketEvent', { text: "Terhubung", color: "white" });
});

channel.leaving(() => {
    Livewire.emit('doorStatusEvent');
});

channel.listen('.door-status', () => {
    Livewire.emit('doorStatusEvent');
});

channel.listen('.door-alert', (data) => {
    Livewire.emit('doorAlertEvent', { name: data.name, message: data.message });
});
