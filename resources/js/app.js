import "./bootstrap";

var index = 0;
var color = 'red';

setInterval(blink, 500);

const channel = Echo.private(`private.dashboard.${user.id}`);

Echo.connector.pusher.connection.bind('connected', () => {
    color = '#90EE90';
    Livewire.emit('socketEvent', 'Terhubung');
});

channel.listen(".door-event", (event) => {
    Livewire.emit('doorEvent', event);
});

function blink() {
    let indicator = document.getElementById('connection_indicator');
    if (index == 0) {
        indicator.style.backgroundColor = color;
        index = 1;
    } else {
        indicator.style.backgroundColor = '';
        index = 0;
    }
}
