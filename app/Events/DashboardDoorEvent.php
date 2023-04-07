<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardDoorEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $id, $socket_id, $is_lock;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $socket_id, $is_lock)
    {
        $this->id = $id;
        $this->socket_id = $socket_id;
        $this->is_lock = $is_lock;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private.dashboard.' . auth()->user()->id);
    }

    public function broadcastAs()
    {
        return 'door-event';
    }

    public function broadcastWith()
    {
        return array(
            'id' => $this->id,
            'socket_id'  => $this->socket_id,
            'is_lock' => $this->is_lock,
        );
    }
}
