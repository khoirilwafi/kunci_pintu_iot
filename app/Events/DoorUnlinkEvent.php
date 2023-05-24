<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoorUnlinkEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $office_id, $door_id, $key;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($office_id, $door_id, $key)
    {
        $this->office_id = $office_id;
        $this->door_id   = $door_id;
        $this->key       = $key;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('office.' . $this->office_id);
    }

    public function broadcastAs()
    {
        return 'door-unlink';
    }

    public function broadcastWith()
    {
        return [
            'door_id' => $this->door_id,
            'key' => $this->key,
        ];
    }
}
