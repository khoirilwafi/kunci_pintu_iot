import "./bootstrap";

const channel = Echo.private(`private.dashboard.${user.id}`);

Echo.connector.pusher.connection.bind('connected', () => {
    Livewire.emit('socketEvent', 'Terhubung');
});

channel.listen(".door-event", (event) => {
    Livewire.emit('doorEvent', event);
});

