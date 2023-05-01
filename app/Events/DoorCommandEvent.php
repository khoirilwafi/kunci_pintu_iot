<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoorCommandEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $office_id, $user_id, $door_id, $locking, $key;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($office_id, $user_id, $door_id, $locking, $key)
    {
        $this->office_id = $office_id;
        $this->user_id = $user_id;
        $this->door_id = $door_id;
        $this->locking = $locking;
        $this->key = $key;
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
        return 'door-command';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user_id,
            'door_id' => $this->door_id,
            'locking' => $this->locking,
            'key' => $this->key,
        ];
    }
}
